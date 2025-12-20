@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Pemilik</h1>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $totalTransaksi }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Transaksi</h3>
            <p class="text-sm text-green-100">Transaksi berhasil</p>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <span class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Pendapatan</h3>
            <p class="text-sm text-purple-100">Semua transaksi</p>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $totalPengguna }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Pengguna</h3>
            <p class="text-sm text-orange-100">User terdaftar</p>
        </div>
    </div>

    <!-- Grafik dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Grafik Penjualan Bulanan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Penjualan 6 Bulan Terakhir
            </h2>
            <div class="relative" style="height: 300px;">
                <canvas id="chartPenjualan"></canvas>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-fire text-red-600 mr-2"></i>
                Top 5 Produk Terlaris
            </h2>
            <div class="space-y-3">
                @forelse($produkTerlaris as $index => $produk)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $produk->nama_produk }}</p>
                            <p class="text-sm text-gray-500">Terjual: {{ $produk->total_terjual }} item</p>
                        </div>
                    </div>
                    <span class="text-green-600 font-bold">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stok Menipis & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                Stok Menipis
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Produk</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Stok</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMenurun as $stok)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->produk->nama_produk }}</td>
                            <td class="px-4 py-3 text-sm text-center font-semibold">
                                {{ $stok->jumlah }} {{ $stok->satuan }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($stok->jumlah < 2)
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Kritis</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Rendah</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                <i class="fas fa-check-circle text-4xl mb-2"></i>
                                <p>Semua stok aman</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-receipt text-green-600 mr-2"></i>
                Transaksi Terbaru
            </h2>
            <div class="space-y-3">
                @forelse($transaksiTerbaru as $transaksi)
                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $transaksi->pengguna->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            {{ ucfirst($transaksi->status_transaksi) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $transaksi->details->count() }} item</span>
                        <span class="text-green-600 font-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-receipt text-4xl mb-2"></i>
                    <p>Belum ada transaksi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartPenjualan');
    
    if (ctx) {
        const chartData = @json($penjualanBulanan);
        
        // Cek apakah ada data
        if (chartData && chartData.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => {
                        const [year, month] = item.bulan.split('-');
                        const date = new Date(year, month - 1);
                        return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: chartData.map(item => parseFloat(item.total)),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + 'Jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000) + 'K';
                                    }
                                    return 'Rp ' + value;
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            // Tampilkan pesan jika tidak ada data
            ctx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-400"><div class="text-center"><i class="fas fa-chart-line text-5xl mb-3"></i><p>Belum ada data penjualan</p></div></div>';
        }
    }
});
</script>
@endpush
@endsection