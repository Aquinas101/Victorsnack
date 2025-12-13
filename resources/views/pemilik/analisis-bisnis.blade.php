@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-pie text-red-600 mr-2"></i>Analisis Bisnis
        </h1>
        <p class="text-gray-600 mt-1">Business Intelligence & Analytics Dashboard</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-box text-3xl opacity-75"></i>
                <p class="text-xs bg-blue-400 bg-opacity-50 px-2 py-1 rounded">Produk</p>
            </div>
            <h3 class="text-4xl font-bold">{{ $totalProduk }}</h3>
            <p class="text-blue-100 text-sm mt-1">Total Produk</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-receipt text-3xl opacity-75"></i>
                <p class="text-xs bg-green-400 bg-opacity-50 px-2 py-1 rounded">Transaksi</p>
            </div>
            <h3 class="text-4xl font-bold">{{ number_format($totalTransaksi) }}</h3>
            <p class="text-green-100 text-sm mt-1">Total Transaksi</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-75"></i>
                <p class="text-xs bg-purple-400 bg-opacity-50 px-2 py-1 rounded">Karyawan</p>
            </div>
            <h3 class="text-4xl font-bold">{{ $totalPengguna }}</h3>
            <p class="text-purple-100 text-sm mt-1">Total Karyawan</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-money-bill-wave text-3xl opacity-75"></i>
                <p class="text-xs bg-orange-400 bg-opacity-50 px-2 py-1 rounded">Pendapatan</p>
            </div>
            <h3 class="text-2xl font-bold">Rp {{ number_format($totalPendapatan / 1000000, 1) }}M</h3>
            <p class="text-orange-100 text-sm mt-1">Total Pendapatan</p>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-chart-line text-3xl opacity-75"></i>
                <p class="text-xs bg-pink-400 bg-opacity-50 px-2 py-1 rounded">Rata-rata</p>
            </div>
            <h3 class="text-2xl font-bold">Rp {{ number_format($rataRataTransaksi / 1000, 0) }}K</h3>
            <p class="text-pink-100 text-sm mt-1">Per Transaksi</p>
        </div>
    </div>

    <!-- Trend 6 Bulan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-area text-red-600 mr-2"></i>Trend Penjualan 6 Bulan Terakhir
        </h2>
        <canvas id="grafikTrend" height="80"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top 5 Produk -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 5 Produk Terlaris
            </h2>
            <div class="space-y-3">
                @foreach($topProduk as $index => $item)
                <div class="flex items-center space-x-3 p-3 border-2 rounded-lg
                    {{ $index == 0 ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }}">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg
                        {{ $index == 0 ? 'bg-yellow-400 text-white' : ($index == 1 ? 'bg-gray-400 text-white' : ($index == 2 ? 'bg-orange-400 text-white' : 'bg-gray-200 text-gray-600')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">{{ $item->varian->produk->nama_produk }}</p>
                        <p class="text-xs text-gray-500">{{ $item->varian->berat }}g - Terjual: {{ $item->total_terjual }} pcs</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 Produk (Kurang Laku) -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Produk Kurang Laku
            </h2>
            <div class="space-y-3">
                @foreach($bottomProduk as $index => $item)
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:shadow-md transition">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-arrow-down font-bold"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $item->varian->produk->nama_produk }}</p>
                        <p class="text-xs text-gray-500">{{ $item->varian->berat }}g - Terjual: {{ $item->total_terjual }} pcs</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                        <p class="text-xs text-red-500">Perlu promo!</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-lightbulb mr-2"></i>
                    <strong>Rekomendasi:</strong> Produk ini memerlukan strategi promosi atau evaluasi harga.
                </p>
            </div>
        </div>
    </div>

    <!-- Performa Karyawan & Kategori -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performa Karyawan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-tie text-red-600 mr-2"></i>Performa Karyawan
            </h2>
            <div class="space-y-3">
                @foreach($perKaryawan as $index => $karyawan)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600' }}">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $karyawan->pengguna->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $karyawan->total_transaksi }} transaksi</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($karyawan->total_penjualan, 0, ',', '.') }}</p>
                        @if($index == 0)
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">
                                <i class="fas fa-star mr-1"></i>Top Performer
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Penjualan per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-tags text-red-600 mr-2"></i>Penjualan per Kategori
            </h2>
            <canvas id="grafikKategori" height="250"></canvas>
        </div>
    </div>

    <!-- Insights & Recommendations -->
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 mt-6 text-white">
        <h2 class="text-xl font-bold mb-4">
            <i class="fas fa-lightbulb mr-2"></i>Business Insights & Rekomendasi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-chart-line text-2xl mt-1"></i>
                    <div>
                        <p class="font-semibold mb-1">Pertumbuhan Bisnis</p>
                        <p class="text-sm text-blue-100">
                            Total {{ number_format($totalTransaksi) }} transaksi telah diproses dengan pendapatan 
                            Rp {{ number_format($totalPendapatan / 1000000, 1) }}M
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-bullseye text-2xl mt-1"></i>
                    <div>
                        <p class="font-semibold mb-1">Target Market</p>
                        <p class="text-sm text-blue-100">
                            Fokus pada produk terlaris dan tingkatkan stok untuk memaksimalkan penjualan
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-users-cog text-2xl mt-1"></i>
                    <div>
                        <p class="font-semibold mb-1">Optimasi Tim</p>
                        <p class="text-sm text-blue-100">
                            {{ $totalPengguna }} karyawan aktif. Berikan reward untuk top performer!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Grafik Trend 6 Bulan
const ctxTrend = document.getElementById('grafikTrend').getContext('2d');
new Chart(ctxTrend, {
    type: 'line',
    data: {
        labels: {!! json_encode($trendBulanan->map(function($item) {
            return \Carbon\Carbon::create()->year($item->tahun)->month($item->bulan)->format('M Y');
        })) !!},
        datasets: [
            {
                label: 'Transaksi',
                data: {!! json_encode($trendBulanan->pluck('transaksi')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                yAxisID: 'y',
            },
            {
                label: 'Pendapatan (Juta Rp)',
                data: {!! json_encode($trendBulanan->pluck('pendapatan')->map(function($p) { return $p / 1000000; })) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Jumlah Transaksi'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Pendapatan (Juta Rp)'
                },
                grid: {
                    drawOnChartArea: false,
                },
            },
        }
    }
});

// Grafik Kategori (Pie Chart)
const ctxKategori = document.getElementById('grafikKategori').getContext('2d');
new Chart(ctxKategori, {
    type: 'pie',
    data: {
        labels: {!! json_encode($perKategori->pluck('kategori')) !!},
        datasets: [{
            data: {!! json_encode($perKategori->pluck('total')) !!},
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection