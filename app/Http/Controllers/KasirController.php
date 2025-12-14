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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $produks = $query->paginate(12);
        $kategoris = Produk::select('kategori')->distinct()->pluck('kategori');

        return view('kasir.index', compact('produks', 'kategoris'));
    }

    /**
     * ✅ Create Midtrans payment token
     */
    public function createPaymentToken(Request $request)
    {
        Log::info('=== CREATE TOKEN REQUEST ===', [
            'user_id' => Auth::id(),
            'items' => $request->items,
            'total' => $request->total_harga
        ]);
        
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'total_harga' => 'required|numeric|min:0',
            ]);

            $serverKey = config('midtrans.server_key');
            
            if (empty($serverKey)) {
                Log::error('❌ SERVER KEY IS NULL!');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Midtrans error: Server Key tidak ditemukan.'
                ], 500);
            }

            // Generate order ID
            $orderId = 'TRX-' . time() . '-' . Auth::id();
            Log::info('Generated Order ID: ' . $orderId);

            // Prepare item details
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

            // Transaction details
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

            // Snap API params
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => ['gopay', 'shopeepay', 'other_qris', 'bca_va', 'bni_va', 'bri_va'],
            ];

            Log::info('Requesting Snap Token...');

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            Log::info('✓ Snap Token Created!');

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('CREATE TOKEN ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
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
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk
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

            // Simpan detail & kurangi stok
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
     * Payment finish dari Midtrans
     */
    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;

        Log::info('Payment Finish', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus
        ]);

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            return redirect()->route('kasir.index')->with('success', 'Pembayaran berhasil! Order ID: ' . $orderId);
        } else if ($transactionStatus == 'pending') {
            return redirect()->route('kasir.index')->with('info', 'Pembayaran pending. Order ID: ' . $orderId);
        } else {
            return redirect()->route('kasir.index')->with('error', 'Pembayaran gagal atau dibatalkan.');
        }
    }

    /**
     * ✅✅✅ FIXED: Callback dari Midtrans Server (Webhook)
     * INI YANG PALING PENTING!
     */
    public function midtransCallback(Request $request)
    {
        try {
            // Set config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Get notification dari Midtrans
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;

            Log::info('=== MIDTRANS WEBHOOK RECEIVED ===', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'raw_data' => $request->all()
            ]);

            DB::beginTransaction();

            // ✅ CRITICAL FIX: Query berdasarkan order_id_midtrans
            $transaksi = Transaksi::where('order_id_midtrans', $orderId)->first();

            if (!$transaksi) {
                Log::warning('❌ Transaction Not Found in Database', ['order_id' => $orderId]);
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            Log::info('✓ Transaction Found', [
                'transaksi_id' => $transaksi->id_transaksi,
                'current_status' => $transaksi->status_transaksi
            ]);

            $oldStatus = $transaksi->status_transaksi;
            $newStatus = 'pending';
            
            // Tentukan status baru berdasarkan notifikasi Midtrans
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
                'new_status' => $newStatus
            ]);

            // Update status transaksi
            $transaksi->update(['status_transaksi' => $newStatus]);

            // ✅ KURANGI STOK jika status berubah ke berhasil
            if ($newStatus === 'berhasil' && $oldStatus !== 'berhasil') {
                Log::info('🔄 Reducing stock for successful payment...');
                
                foreach ($transaksi->details as $detail) {
                    $varian = $detail->varian;
                    $stok = Stok::where('id_produk', $varian->id_produk)->first();
                    
                    if ($stok) {
                        $totalGramDibeli = $varian->berat * $detail->jumlah;
                        
                        if (!$stok->cekStokTersedia($totalGramDibeli)) {
                            DB::rollBack();
                            Log::error('❌ Insufficient Stock', [
                                'order_id' => $orderId,
                                'produk' => $varian->produk->nama_produk,
                                'required' => $totalGramDibeli,
                                'available' => $stok->getStokDalamGram()
                            ]);
                            
                            return response()->json([
                                'success' => false,
                                'message' => 'Stok tidak mencukupi'
                            ], 400);
                        }
                        
                        $stok->kurangiStok($totalGramDibeli);
                        
                        Log::info('✓ Stock Reduced', [
                            'produk' => $varian->produk->nama_produk,
                            'reduced' => $totalGramDibeli . 'g',
                            'remaining' => $stok->getStokFormatted()
                        ]);
                    }
                }
                
                Log::info('✅ All stock successfully reduced!');
            }

            DB::commit();

            Log::info('✅✅✅ WEBHOOK PROCESSED SUCCESSFULLY', [
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
            
            Log::error('❌❌❌ WEBHOOK ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ FIXED: Process Midtrans Payment
     */
    public function processMidtransPayment(Request $request)
    {
        Log::info('=== PROCESS PAYMENT REQUEST ===', [
            'order_id' => $request->order_id,
            'items_count' => count($request->items ?? [])
        ]);

        $request->validate([
            'order_id' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id_varian' => 'required|exists:varian_produk,id_varian',
            'items.*.jumlah' => 'required|integer|min:1',
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
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk
                    ], 400);
                }
            }

            // Hitung total
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                $totalHarga += $varian->harga * $item['jumlah'];
            }

            // Parse user ID dari order_id
            $orderParts = explode('-', $request->order_id);
            $userId = end($orderParts);

            // ✅ FIX: Gunakan Auth::id() sebagai fallback
            if (!is_numeric($userId) || $userId <= 0) {
                $userId = Auth::id();
            }

            Log::info('Creating transaction', [
                'order_id' => $request->order_id,
                'user_id' => $userId,
                'total' => $totalHarga
            ]);

            // ✅ Buat transaksi dengan status pending
            $transaksi = Transaksi::create([
                'order_id_midtrans' => $request->order_id,
                'id_pengguna' => $userId,
                'total_harga' => $totalHarga,
                'metode_pembayaran' => 'dompet_digital',
                'status_transaksi' => 'pending', // ✅ PENDING dulu, nanti webhook yang ubah
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_varian' => $item['id_varian'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $varian->harga * $item['jumlah'],
                ]);
            }

            DB::commit();

            Log::info('✅ Transaction Created Successfully (Pending)', [
                'transaksi_id' => $transaksi->id_transaksi,
                'order_id' => $request->order_id,
                'note' => 'Stock will be reduced when webhook confirms payment'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat (pending payment)',
                'transaksi_id' => $transaksi->id_transaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('❌ Process Payment Error', [
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
     * Get detail varian
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
     * Check stok produk
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