<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\VarianProduk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Untuk Admin & Karyawan - melihat semua transaksi
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pengguna', 'details.varian.produk'])
                          ->latest('tanggal_transaksi');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_transaksi', $request->tanggal);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        $transaksis = $query->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     * Tidak digunakan - transaksi dibuat dari kasir
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Untuk menyimpan transaksi dari kasir
     */
    public function store(Request $request)
    {
        $request->validate([
            'keranjang' => 'required|array|min:1',
            'keranjang.*.id_varian' => 'required|exists:varian_produk,id_varian',
            'keranjang.*.jumlah' => 'required|integer|min:1',
            'keranjang.*.subtotal' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,kredit,debit,dompet_digital',
            'uang_dibayar' => 'nullable|numeric|min:0',
        ], [
            'keranjang.required' => 'Keranjang tidak boleh kosong',
            'keranjang.min' => 'Minimal harus ada 1 produk',
            'total_harga.required' => 'Total harga harus diisi',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
        ]);

        DB::beginTransaction();
        try {
            // Cek stok untuk semua item
            foreach ($request->keranjang as $item) {
                $varian = VarianProduk::findOrFail($item['id_varian']);
                $stok = Stok::where('id_produk', $varian->id_produk)->first();
                
                if (!$stok || !$stok->cekStokTersedia($item['jumlah'])) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Stok tidak mencukupi untuk produk: ' . $varian->produk->nama_produk);
                }
            }

            // Hitung kembalian jika tunai
            $uangDibayar = null;
            $kembalian = null;
            
            if ($request->metode_pembayaran === 'tunai' && $request->filled('uang_dibayar')) {
                $uangDibayar = $request->uang_dibayar;
                $kembalian = $uangDibayar - $request->total_harga;
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'id_pengguna' => Auth::id(),
                'total_harga' => $request->total_harga,
                'uang_dibayar' => $uangDibayar,
                'kembalian' => $kembalian,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_transaksi' => 'berhasil',
            ]);

            // Simpan detail transaksi dan kurangi stok
            foreach ($request->keranjang as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_varian' => $item['id_varian'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok
                $varian = VarianProduk::findOrFail($item['id_varian']);
                $stok = Stok::where('id_produk', $varian->id_produk)->first();
                $stok->kurangiStok($item['jumlah']);
            }

            DB::commit();

            return redirect()->route(role_route('transaksi.show'), $transaksi->id_transaksi)
                ->with('success', 'Transaksi berhasil! ID: ' . $transaksi->id_transaksi);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Detail transaksi
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['pengguna', 'details.varian.produk'])
                              ->findOrFail($id);
        
        return view('transaksi.detail', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     * Tidak digunakan - transaksi tidak bisa diedit
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Hanya untuk update status transaksi
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'status_transaksi' => 'required|in:berhasil,pending,gagal',
        ]);

        try {
            $transaksi->update([
                'status_transaksi' => $request->status_transaksi,
            ]);

            return redirect()->back()
                ->with('success', 'Status transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Untuk membatalkan transaksi (kembalikan stok)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::with('details.varian')->findOrFail($id);

            // Kembalikan stok jika transaksi berhasil
            if ($transaksi->status_transaksi === 'berhasil') {
                foreach ($transaksi->details as $detail) {
                    $stok = Stok::where('id_produk', $detail->varian->id_produk)->first();
                    if ($stok) {
                        $stok->tambahStok($detail->jumlah);
                    }
                }
            }

            $transaksi->delete();
            DB::commit();

            return redirect()->route(role_route('transaksi.index'))
                ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Transaksi untuk kasir - hanya transaksi mereka sendiri (hari ini)
     */
    public function transaksiSaya()
    {
        $transaksis = Transaksi::with(['details.varian.produk'])
                               ->where('id_pengguna', Auth::id())
                               ->latest('tanggal_transaksi')
                               ->paginate(10);

        return view('kasir.transaksi-saya', compact('transaksis'));
    }

    /**
     * Riwayat pemesanan untuk kasir (history lengkap dengan filter & analytics)
     */
    public function riwayat(Request $request)
    {
        $query = Transaksi::with(['details.varian.produk'])
                         ->where('id_pengguna', Auth::id());

        // Search by ID transaksi atau tanggal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_transaksi', 'like', "%{$search}%")
                  ->orWhereDate('tanggal_transaksi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan periode
        if ($request->filled('periode')) {
            $days = (int) $request->periode;
            $query->where('tanggal_transaksi', '>=', now()->subDays($days));
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        $transaksis = $query->latest('tanggal_transaksi')->paginate(15);

        return view('kasir.riwayat', compact('transaksis'));
    }

    /**
     * Cetak struk transaksi
     */
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['pengguna', 'details.varian.produk'])
                              ->findOrFail($id);
        
        return view('transaksi.struk', compact('transaksi'));
    }
}