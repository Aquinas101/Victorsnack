@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Karyawan</h1>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Produk -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $totalProduk }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Produk</h3>
            <p class="text-sm text-blue-100">Produk terdaftar</p>
        </div>

        <!-- Total Stok -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-warehouse text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ number_format($totalStok, 0) }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Stok</h3>
            <p class="text-sm text-green-100">Dalam kilogram (kg)</p>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $transaksiHariIni }}</span>
            </div>
            <h3 class="text-lg font-semibold">Transaksi Hari Ini</h3>
            <p class="text-sm text-purple-100">{{ now()->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Stok per Kategori -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
            Ringkasan Stok per Kategori
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($stokPerKategori as $kategori)
            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border-l-4 border-blue-600 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-bold text-gray-800 text-lg">{{ ucfirst($kategori->kategori) }}</h3>
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div class="space-y-1">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-boxes text-gray-400 mr-1"></i>
                        {{ $kategori->jumlah_produk }} Produk
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-weight text-gray-400 mr-1"></i>
                        {{ number_format($kategori->total_stok, 2) }} kg
                    </p>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-400">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Belum ada data stok</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Stok Menipis
                </h2>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                    {{ $stokMenurun->count() }} Produk
                </span>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="w-full">
                    <thead class="sticky top-0 bg-gray-50">
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Produk</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Kategori</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Stok</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMenurun as $stok)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800 font-medium">
                                {{ $stok->produk->nama_produk }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                    {{ ucfirst($stok->produk->kategori) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center font-bold">
                                {{ $stok->jumlah }} {{ $stok->satuan }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($stok->jumlah < 2)
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Kritis
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Rendah
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                <i class="fas fa-check-circle text-4xl mb-2 text-green-400"></i>
                                <p>Semua stok aman!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($stokMenurun->count() > 0)
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('karyawan.stok.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center justify-center">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Kelola Stok
                </a>
            </div>
            @endif
        </div>

        <!-- Produk Terbaru -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-star text-yellow-600 mr-2"></i>
                    Produk Terbaru
                </h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                    5 Terakhir
                </span>
            </div>
            <div class="space-y-3">
                @forelse($produkTerbaru as $produk)
                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:shadow-md transition border-l-4 border-blue-600">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 mb-1">{{ $produk->nama_produk }}</h3>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                {{ ucfirst($produk->kategori) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                {{ $produk->create_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-layer-group text-blue-600 mr-1"></i>
                            {{ $produk->varians->count() }} Varian
                        </span>
                        <a href="{{ route('karyawan.produk.edit', $produk->id_produk) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>Belum ada produk</p>
                </div>
                @endforelse
            </div>
            @if($produkTerbaru->count() > 0)
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('karyawan.produk.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center justify-center">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Lihat Semua Produk
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-bolt mr-2"></i>
            Aksi Cepat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('karyawan.produk.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-plus-circle text-2xl"></i>
                <span class="font-semibold">Tambah Produk</span>
            </a>
            <a href="{{ route('karyawan.stok.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-warehouse text-2xl"></i>
                <span class="font-semibold">Kelola Stok</span>
            </a>
            <a href="{{ route('karyawan.varian.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-layer-group text-2xl"></i>
                <span class="font-semibold">Tambah Varian</span>
            </a>
        </div>
    </div>
</div>
@endsection