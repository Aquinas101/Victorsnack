<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Pengguna;
use App\Models\Stok;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk PEMILIK (Admin)
     */
    public function admin()
    {
        // Total Produk
        $totalProduk = Produk::count();
        
        // Total Transaksi
        $totalTransaksi = Transaksi::where('status_transaksi', 'berhasil')->count();
        
        // Total Pendapatan
        $totalPendapatan = Transaksi::where('status_transaksi', 'berhasil')->sum('total_harga');
        
        // Total Pengguna
        $totalPengguna = Pengguna::count();
        
        // Produk Stok Menipis (kurang dari 5 kg)
        $stokMenurun = Stok::with('produk')
            ->where('jumlah', '<', 5)
            ->orderBy('jumlah', 'asc')
            ->limit(5)
            ->get();
        
        // Transaksi Terbaru (5 terakhir) - FIXED: gunakan 'details' bukan 'detailTransaksi'
        $transaksiTerbaru = Transaksi::with(['pengguna', 'details.varian.produk'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();
        
        // Statistik Penjualan per Bulan (6 bulan terakhir)
        $penjualanBulanan = Transaksi::select(
                DB::raw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('status_transaksi', 'berhasil')
            ->where('tanggal_transaksi', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
        
        // Produk Terlaris (Top 5)
        $produkTerlaris = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->select(
                'produk.nama_produk',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalProduk',
            'totalTransaksi',
            'totalPendapatan',
            'totalPengguna',
            'stokMenurun',
            'transaksiTerbaru',
            'penjualanBulanan',
            'produkTerlaris'
        ));
    }

    /**
     * Dashboard untuk KARYAWAN
     */
    public function karyawan()
    {
        // Total Produk
        $totalProduk = Produk::count();
        
        // Total Stok (dalam kg)
        $totalStok = Stok::sum('jumlah');
        
        // Produk Stok Menipis (kurang dari 5 kg)
        $stokMenurun = Stok::with('produk')
            ->where('jumlah', '<', 5)
            ->orderBy('jumlah', 'asc')
            ->limit(10)
            ->get();
        
        // Produk Terbaru (5 terakhir)
        $produkTerbaru = Produk::with('varians')
            ->orderBy('create_at', 'desc')
            ->limit(5)
            ->get();
        
        // Total Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', today())
            ->where('status_transaksi', 'berhasil')
            ->count();
        
        // Ringkasan Stok per Kategori
        $stokPerKategori = DB::table('produk')
            ->join('stok', 'produk.id_produk', '=', 'stok.id_produk')
            ->select(
                'produk.kategori',
                DB::raw('COUNT(produk.id_produk) as jumlah_produk'),
                DB::raw('SUM(stok.jumlah) as total_stok')
            )
            ->groupBy('produk.kategori')
            ->get();
        
        return view('dashboard.karyawan', compact(
            'totalProduk',
            'totalStok',
            'stokMenurun',
            'produkTerbaru',
            'transaksiHariIni',
            'stokPerKategori'
        ));
    }

    /**
     * Dashboard untuk KASIR
     */
    public function kasir()
    {
        $userId = Auth::id();
        
        // Total Transaksi Kasir Hari Ini
        $transaksiHariIni = Transaksi::where('id_pengguna', $userId)
            ->whereDate('tanggal_transaksi', today())
            ->where('status_transaksi', 'berhasil')
            ->count();
        
        // Total Pendapatan Kasir Hari Ini
        $pendapatanHariIni = Transaksi::where('id_pengguna', $userId)
            ->whereDate('tanggal_transaksi', today())
            ->where('status_transaksi', 'berhasil')
            ->sum('total_harga');
        
        // Total Transaksi Kasir (Keseluruhan)
        $totalTransaksi = Transaksi::where('id_pengguna', $userId)
            ->where('status_transaksi', 'berhasil')
            ->count();
        
        // Total Pendapatan Kasir (Keseluruhan)
        $totalPendapatan = Transaksi::where('id_pengguna', $userId)
            ->where('status_transaksi', 'berhasil')
            ->sum('total_harga');
        
        // Transaksi Terbaru Kasir (5 terakhir) - FIXED: gunakan 'details' bukan 'detailTransaksi'
        $transaksiTerbaru = Transaksi::with(['details.varian.produk'])
            ->where('id_pengguna', $userId)
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();
        
        // Statistik per Metode Pembayaran (Hari Ini)
        $pembayaranHariIni = Transaksi::select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('id_pengguna', $userId)
            ->whereDate('tanggal_transaksi', today())
            ->where('status_transaksi', 'berhasil')
            ->groupBy('metode_pembayaran')
            ->get();
        
        // Produk Tersedia (dengan stok > 0)
        $produkTersedia = Produk::whereHas('stok', function($query) {
            $query->where('jumlah', '>', 0);
        })->count();
        
        // Grafik Penjualan 7 Hari Terakhir
        $penjualan7Hari = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('id_pengguna', $userId)
            ->where('status_transaksi', 'berhasil')
            ->where('tanggal_transaksi', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        return view('dashboard.kasir', compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'totalTransaksi',
            'totalPendapatan',
            'transaksiTerbaru',
            'pembayaranHariIni',
            'produkTersedia',
            'penjualan7Hari'
        ));
    }
}