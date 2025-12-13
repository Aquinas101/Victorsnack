<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pengguna;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Laporan Penjualan
     * Menampilkan data penjualan dengan filter tanggal
     */
    public function penjualan(Request $request)
    {
        // Default periode: 30 hari terakhir
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        // Query transaksi berdasarkan filter
        $query = Transaksi::with(['pengguna', 'details.varian.produk'])
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()]);

        // Filter berdasarkan kasir
        if ($request->filled('kasir')) {
            $query->where('id_pengguna', $request->kasir);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        $transaksis = $query->latest('tanggal_transaksi')->paginate(15);

        // Summary statistik
        $totalTransaksi = $query->count();
        $totalPendapatan = $query->sum('total_harga');
        $totalItem = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('detail_transaksi.jumlah');
        
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // Data untuk filter
        $kasirs = Pengguna::where('role', 'kasir')->get();

        // Grafik penjualan per hari
        $grafikHarian = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('laporan.penjualan', compact(
            'transaksis',
            'totalTransaksi',
            'totalPendapatan',
            'totalItem',
            'rataRataTransaksi',
            'kasirs',
            'startDate',
            'endDate',
            'grafikHarian'
        ));
    }

    /**
     * Laporan Keuangan
     * Menampilkan ringkasan keuangan per metode pembayaran
     */
    public function keuangan(Request $request)
    {
        // Default periode: bulan ini
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->startOfMonth();
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        // Total pendapatan berhasil
        $totalPendapatan = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('total_harga');

        // Total transaksi berhasil
        $totalTransaksiBerhasil = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        // Total transaksi pending
        $totalTransaksiPending = Transaksi::where('status_transaksi', 'pending')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        // Total transaksi gagal
        $totalTransaksiGagal = Transaksi::where('status_transaksi', 'gagal')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        // Breakdown per metode pembayaran
        $perMetodePembayaran = Transaksi::select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('metode_pembayaran')
            ->get();

        // Grafik pendapatan bulanan (6 bulan terakhir)
        $grafikBulanan = Transaksi::select(
                DB::raw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->where('tanggal_transaksi', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Performa kasir
        $perKasir = Transaksi::select(
                'pengguna.nama_lengkap',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(transaksi.total_harga) as total_pendapatan')
            )
            ->join('pengguna', 'transaksi.id_pengguna', '=', 'pengguna.id_pengguna')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('pengguna.id_pengguna', 'pengguna.nama_lengkap')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        return view('laporan.keuangan', compact(
            'totalPendapatan',
            'totalTransaksiBerhasil',
            'totalTransaksiPending',
            'totalTransaksiGagal',
            'perMetodePembayaran',
            'grafikBulanan',
            'perKasir',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Analisis Bisnis
     * Menampilkan produk terlaris, kategori terpopuler, dll
     */
    public function analisis(Request $request)
    {
        // Default periode: 30 hari terakhir
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        // Produk Terlaris (Top 10)
        $produkTerlaris = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.nama_produk',
                'produk.kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.kategori')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        // Kategori Terpopuler
        $kategoriTerpopuler = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.kategori',
                DB::raw('COUNT(DISTINCT produk.id_produk) as jumlah_produk'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.kategori')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        // Jam Tersibuk
        $jamTersibuk = Transaksi::select(
                DB::raw('HOUR(tanggal_transaksi) as jam'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('jam')
            ->orderBy('jumlah_transaksi', 'desc')
            ->get();

        // Hari Tersibuk
        $hariTersibuk = Transaksi::select(
                DB::raw('DAYNAME(tanggal_transaksi) as hari'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('hari')
            ->orderBy('jumlah_transaksi', 'desc')
            ->get();

        // Varian Terpopuler
        $varianTerpopuler = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.nama_produk',
                'varian_produk.berat',
                'varian_produk.harga',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('varian_produk.id_varian', 'produk.nama_produk', 'varian_produk.berat', 'varian_produk.harga')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        // Total Summary
        $totalProdukTerjual = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('detail_transaksi.jumlah');

        $totalPendapatan = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('total_harga');

        return view('laporan.analisis', compact(
            'produkTerlaris',
            'kategoriTerpopuler',
            'jamTersibuk',
            'hariTersibuk',
            'varianTerpopuler',
            'totalProdukTerjual',
            'totalPendapatan',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Halaman utama laporan (untuk admin)
     */
    public function admin()
    {
        // Redirect ke laporan penjualan sebagai default
        return redirect()->route('admin.laporan.penjualan');
    }

    /**
     * Export Laporan Penjualan ke PDF
     */
    public function exportPenjualanPDF(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        $query = Transaksi::with(['pengguna', 'details.varian.produk'])
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if ($request->filled('kasir')) {
            $query->where('id_pengguna', $request->kasir);
        }

        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        $transaksis = $query->latest('tanggal_transaksi')->get();
        $totalTransaksi = $query->count();
        $totalPendapatan = $query->sum('total_harga');
        $totalItem = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('detail_transaksi.jumlah');
        
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        $pdf = Pdf::loadView('laporan.pdf.penjualan', compact(
            'transaksis',
            'totalTransaksi',
            'totalPendapatan',
            'totalItem',
            'rataRataTransaksi',
            'startDate',
            'endDate'
        ));

        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan-Penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Keuangan ke PDF
     */
    public function exportKeuanganPDF(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->startOfMonth();
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        $totalPendapatan = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('total_harga');

        $totalTransaksiBerhasil = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $totalTransaksiPending = Transaksi::where('status_transaksi', 'pending')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $totalTransaksiGagal = Transaksi::where('status_transaksi', 'gagal')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $perMetodePembayaran = Transaksi::select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('metode_pembayaran')
            ->get();

        $perKasir = Transaksi::select(
                'pengguna.nama_lengkap',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(transaksi.total_harga) as total_pendapatan')
            )
            ->join('pengguna', 'transaksi.id_pengguna', '=', 'pengguna.id_pengguna')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('pengguna.id_pengguna', 'pengguna.nama_lengkap')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.keuangan', compact(
            'totalPendapatan',
            'totalTransaksiBerhasil',
            'totalTransaksiPending',
            'totalTransaksiGagal',
            'perMetodePembayaran',
            'perKasir',
            'startDate',
            'endDate'
        ));

        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan-Keuangan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Analisis ke PDF
     */
    public function exportAnalisisPDF(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now();

        $produkTerlaris = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.nama_produk',
                'produk.kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.kategori')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        $kategoriTerpopuler = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.kategori',
                DB::raw('COUNT(DISTINCT produk.id_produk) as jumlah_produk'),
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.kategori')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        $varianTerpopuler = DB::table('detail_transaksi')
            ->join('varian_produk', 'detail_transaksi.id_varian', '=', 'varian_produk.id_varian')
            ->join('produk', 'varian_produk.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(
                'produk.nama_produk',
                'varian_produk.berat',
                'varian_produk.harga',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('varian_produk.id_varian', 'produk.nama_produk', 'varian_produk.berat', 'varian_produk.harga')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        $totalProdukTerjual = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_transaksi', 'berhasil')
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('detail_transaksi.jumlah');

        $totalPendapatan = Transaksi::where('status_transaksi', 'berhasil')
            ->whereBetween('tanggal_transaksi', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('total_harga');

        $pdf = Pdf::loadView('laporan.pdf.analisis', compact(
            'produkTerlaris',
            'kategoriTerpopuler',
            'varianTerpopuler',
            'totalProdukTerjual',
            'totalPendapatan',
            'startDate',
            'endDate'
        ));

        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan-Analisis-' . now()->format('Y-m-d') . '.pdf');
    }
}