<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\VarianProduk;
use App\Models\Stok;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class KasirController extends Controller
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Display kasir page (POS)
     */
    public function index(Request $request)
    {
        $query = Produk::with(['varians', 'stok']);

        // Search produk
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $produks = $query->paginate(12);
        
        // Get semua kategori untuk filter
        $kategoris = Produk::select('kategori')->distinct()->pluck('kategori');

        return view('kasir.index', compact('produks', 'kategoris'));
    }

    /**
     * Create Midtrans payment token
     */
    public function createPaymentToken(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total_harga' => 'required|numeric|min:0',
        ]);

        try {
            // Generate unique order ID
            $orderId = 'TRX-' . time() . '-' . Auth::id();

            // Prepare item details for Midtrans
            $itemDetails = [];
            foreach ($request->items as $item) {
                $varian = VarianProduk::with('produk')->findOrFail($item['id_varian']);
                
                $itemDetails[] = [
                    'id' => $varian->id_varian,
                    'price' => (int) $varian->harga,
                    'quantity' => $item['jumlah'],
                    'name' => $varian->produk->nama_produk . ' (' . $varian->berat . 'g)',
                ];
            }

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total_harga,
            ];

            // Customer details
            $customerDetails = [
                'first_name' => Auth::user()->nama_lengkap,
                'email' => Auth::user()->username . '@victorsnack.com',
                'phone' => '08123456789',
            ];

            // Prepare Snap API payload
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => ['credit_card', 'gopay', 'shopeepay', 'other_qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va'],
                'callbacks' => [
                    'finish' => route('kasir.payment-finish')
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Log untuk debugging
            Log::info('Midtrans Token Created', [
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Token Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat payment token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process transaksi (untuk Tunai)
     */
    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_varian' => 'required|exists:varian_produk,id_varian',
            'items.*.jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,kredit,debit,dompet_digital',
            'uang_diterima' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Validasi stok
            foreach ($request->items as $item) {
                $varian = VarianProduk::with('produk')->findOrFail($item['id_varian']);
                $stok = Stok::where('id_produk', $varian->id_produk)->first();
                
                if (!$stok) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak ditemukan untuk produk: ' . $varian->produk->nama_produk
                    ], 400);
                }
                
                $totalGramDibeli = $varian->berat * $item['jumlah'];
                
                if (!$stok->cekStokTersedia($totalGramDibeli)) {
                    DB::rollBack();
                    
                    $stokTersediaGram = $stok->getStokDalamGram();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk . 
                                    '! Stok tersedia: ' . $stok->getStokFormatted() . 
                                    ' (' . number_format($stokTersediaGram, 0) . 'g), ' .
                                    'dibutuhkan: ' . number_format($totalGramDibeli, 0) . 'g'
                    ], 400);
                }
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'id_pengguna' => Auth::id(),
                'total_harga' => $request->total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_transaksi' => 'berhasil',
            ]);

            // Simpan detail transaksi dan kurangi stok
            foreach ($request->items as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_varian' => $item['id_varian'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $varian->harga * $item['jumlah'],
                ]);

                $stok = Stok::where('id_produk', $varian->id_produk)->first();
                $totalGramDibeli = $varian->berat * $item['jumlah'];
                $stok->kurangiStok($totalGramDibeli);
            }

            DB::commit();

            $kembalian = 0;
            if ($request->metode_pembayaran === 'tunai' && $request->filled('uang_diterima')) {
                $kembalian = $request->uang_diterima - $request->total_harga;
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'data' => [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'total_harga' => $transaksi->total_harga,
                    'kembalian' => $kembalian,
                    'cetak_url' => route('kasir.transaksi.cetak', $transaksi->id_transaksi),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaksi Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment finish dari Midtrans (user kembali ke website)
     */
    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        Log::info('Payment Finish', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus
        ]);

        // Redirect ke halaman kasir dengan pesan
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            return redirect()->route('kasir.index')->with('success', 'Pembayaran berhasil! Order ID: ' . $orderId);
        } else if ($transactionStatus == 'pending') {
            return redirect()->route('kasir.index')->with('info', 'Pembayaran pending. Order ID: ' . $orderId);
        } else {
            return redirect()->route('kasir.index')->with('error', 'Pembayaran gagal atau dibatalkan.');
        }
    }

    /**
     * ✅ FIXED: Callback dari Midtrans Server (Webhook/Notification)
     */
    public function midtransCallback(Request $request)
    {
        try {
            // Set Config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Get notification dari Midtrans
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            $grossAmount = $notification->gross_amount;

            // Log notification
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'raw_notification' => $request->all()
            ]);

            DB::beginTransaction();

            // ✅ Cari transaksi berdasarkan order_id_midtrans
            $transaksi = Transaksi::where('order_id_midtrans', $orderId)->first();

            if (!$transaksi) {
                Log::warning('Transaction Not Found', ['order_id' => $orderId]);
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Status lama
            $oldStatus = $transaksi->status_transaksi;

            // Tentukan status transaksi baru
            $newStatus = 'pending';
            
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    $newStatus = ($fraudStatus == 'accept') ? 'berhasil' : 'pending';
                }
            } else if ($transactionStatus == 'settlement') {
                $newStatus = 'berhasil';
            } else if ($transactionStatus == 'pending') {
                $newStatus = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newStatus = 'gagal';
            }

            Log::info('Status Transition', [
                'order_id' => $orderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'transaction_status' => $transactionStatus
            ]);

            // Update status transaksi
            $transaksi->update([
                'status_transaksi' => $newStatus
            ]);

            // ✅ KURANGI STOK JIKA STATUS BERUBAH DARI PENDING/GAGAL KE BERHASIL
            if ($newStatus === 'berhasil' && $oldStatus !== 'berhasil') {
                Log::info('Attempting to reduce stock', ['order_id' => $orderId]);
                
                foreach ($transaksi->details as $detail) {
                    $varian = $detail->varian;
                    $stok = Stok::where('id_produk', $varian->id_produk)->first();
                    
                    if ($stok) {
                        $totalGramDibeli = $varian->berat * $detail->jumlah;
                        
                        // Validasi stok tersedia
                        if (!$stok->cekStokTersedia($totalGramDibeli)) {
                            DB::rollBack();
                            Log::error('Insufficient Stock', [
                                'order_id' => $orderId,
                                'produk' => $varian->produk->nama_produk,
                                'required' => $totalGramDibeli,
                                'available' => $stok->getStokDalamGram()
                            ]);
                            
                            return response()->json([
                                'success' => false,
                                'message' => 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk
                            ], 400);
                        }
                        
                        // Kurangi stok
                        $stok->kurangiStok($totalGramDibeli);
                        
                        Log::info('Stock Reduced', [
                            'order_id' => $orderId,
                            'produk' => $varian->produk->nama_produk,
                            'jumlah_dikurangi' => $totalGramDibeli . 'g',
                            'stok_tersisa' => $stok->getStokFormatted()
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Transaction Updated Successfully', [
                'order_id' => $orderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'stock_reduced' => ($newStatus === 'berhasil' && $oldStatus !== 'berhasil')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Midtrans Callback Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Proses pembayaran Midtrans setelah user klik bayar di frontend
     */
    public function processMidtransPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Parse items
            $items = $request->items;

            // Validasi stok
            foreach ($items as $item) {
                $varian = VarianProduk::with('produk')->findOrFail($item['id_varian']);
                $stok = Stok::where('id_produk', $varian->id_produk)->first();
                
                if (!$stok) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak ditemukan untuk produk: ' . $varian->produk->nama_produk
                    ], 400);
                }
                
                $totalGramDibeli = $varian->berat * $item['jumlah'];
                
                if (!$stok->cekStokTersedia($totalGramDibeli)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk
                    ], 400);
                }
            }

            // Hitung total
            $totalHarga = 0;
            foreach ($items as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                $totalHarga += $varian->harga * $item['jumlah'];
            }

            // Parse user ID dari order_id
            $orderParts = explode('-', $request->order_id);
            $userId = end($orderParts);

            // Buat transaksi dengan status pending (stok belum dikurangi)
            $transaksi = Transaksi::create([
                'order_id_midtrans' => $request->order_id,
                'id_pengguna' => $userId,
                'total_harga' => $totalHarga,
                'metode_pembayaran' => 'dompet_digital',
                'status_transaksi' => 'pending', // ⚠️ Status pending, stok BELUM dikurangi
            ]);

            // Simpan detail transaksi (TANPA kurangi stok dulu)
            foreach ($items as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_varian' => $item['id_varian'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $varian->harga * $item['jumlah'],
                ]);
            }

            DB::commit();

            Log::info('Midtrans Transaction Created (Pending)', [
                'order_id' => $request->order_id,
                'transaksi_id' => $transaksi->id_transaksi,
                'status' => 'pending',
                'note' => 'Stock will be reduced when webhook receives payment success'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat (pending payment)',
                'transaksi_id' => $transaksi->id_transaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Process Midtrans Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail varian (untuk AJAX)
     */
    public function getVarian($id)
    {
        try {
            $varian = VarianProduk::with('produk')->findOrFail($id);
            $stok = Stok::where('id_produk', $varian->id_produk)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'id_varian' => $varian->id_varian,
                    'nama_produk' => $varian->produk->nama_produk,
                    'kategori' => $varian->produk->kategori,
                    'berat' => $varian->berat,
                    'harga' => $varian->harga,
                    'stok_tersedia' => $stok ? $stok->jumlah : 0,
                    'stok_formatted' => $stok ? $stok->getStokFormatted() : '0',
                    'stok_dalam_gram' => $stok ? $stok->getStokDalamGram() : 0,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Varian tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Check stok produk (untuk AJAX)
     */
    public function checkStok($id_produk)
    {
        try {
            $stok = Stok::where('id_produk', $id_produk)->first();
            
            return response()->json([
                'success' => true,
                'stok' => $stok ? $stok->jumlah : 0,
                'stok_formatted' => $stok ? $stok->getStokFormatted() : '0',
                'stok_dalam_gram' => $stok ? $stok->getStokDalamGram() : 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak ditemukan'
            ], 404);
        }
    }
}