<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController; 
use App\Http\Controllers\VarianProdukController; 
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

// 🔹 Halaman Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔹 Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ✅✅✅ MIDTRANS WEBHOOK (HARUS di luar middleware auth karena dipanggil oleh server Midtrans)
// PENTING: URL ini HARUS sama dengan yang di set di Midtrans Dashboard


// 🔸 PEMILIK (ADMIN) - Akses Penuh
Route::middleware(['auth', 'role:pemilik'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::put('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/change', [PenggunaController::class, 'changePassword'])->name('password.change');
    Route::put('/password/update', [PenggunaController::class, 'updatePassword'])->name('password.update');
    
    Route::resource('produk', ProdukController::class);
    Route::resource('varian', VarianProdukController::class);
    Route::resource('stok', StokController::class);
    Route::resource('transaksi', TransaksiController::class)->except(['create', 'edit']);
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');
    
    Route::resource('pengguna', PenggunaController::class);
    Route::post('/pengguna/{id}/reset-password', [PenggunaController::class, 'resetPassword'])->name('pengguna.reset-password');
    
    Route::get('/laporan-penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan-keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/analisis-bisnis', [LaporanController::class, 'analisis'])->name('laporan.analisis');
    Route::get('/laporan', [LaporanController::class, 'admin'])->name('laporan.admin');
    Route::get('/laporan-penjualan/export-pdf', [LaporanController::class, 'exportPenjualanPDF'])->name('laporan.penjualan.pdf');
    Route::get('/laporan-keuangan/export-pdf', [LaporanController::class, 'exportKeuanganPDF'])->name('laporan.keuangan.pdf');
});

// 🔸 KARYAWAN
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('dashboard');

    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::put('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/change', [PenggunaController::class, 'changePassword'])->name('password.change');
    Route::put('/password/update', [PenggunaController::class, 'updatePassword'])->name('password.update');
    
    Route::resource('produk', ProdukController::class);
    Route::resource('varian', VarianProdukController::class);
    Route::resource('stok', StokController::class);
    
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');
});

// 🔸 KASIR
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kasir'])->name('dashboard');

    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::put('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/change', [PenggunaController::class, 'changePassword'])->name('password.change');
    Route::put('/password/update', [PenggunaController::class, 'updatePassword'])->name('password.update');

    // Kasir POS
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::post('/proses', [KasirController::class, 'prosesTransaksi'])->name('proses');
    
    // ✅ Midtrans Routes (untuk create token dan process payment)
    Route::post('/create-token', [KasirController::class, 'createPaymentToken'])->name('create-token');
    Route::post('/process-payment', [KasirController::class, 'processMidtransPayment'])->name('process-payment');
    Route::get('/payment-finish', [KasirController::class, 'paymentFinish'])->name('payment-finish');
    
    Route::get('/varian/{id}', [KasirController::class, 'getVarian'])->name('varian');
    Route::get('/stok/{id_produk}', [KasirController::class, 'checkStok'])->name('stok');
    
    Route::get('/transaksi-saya', [TransaksiController::class, 'transaksiSaya'])->name('transaksi.saya');
    Route::get('/riwayat-pemesanan', [TransaksiController::class, 'riwayat'])->name('riwayat.index');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.detail');
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');
    
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
});

// 🔹 Redirect default
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        
        if ($user->role === 'pemilik') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('karyawan.dashboard');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
    }
    
    return redirect('/login');
});