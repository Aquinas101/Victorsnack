@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-money-bill-wave text-red-600 mr-2"></i>Laporan Keuangan
        </h1>
        <p class="text-gray-600 mt-1">Ringkasan keuangan dan pendapatan</p>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form action="{{ route('laporan.keuangan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Pendapatan</p>
                    <p class="text-xs text-green-200">{{ $tanggalMulai->format('F Y') }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-4xl"></i>
                </div>
            </div>
            <h3 class="text-4xl font-bold mb-4">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            
            <!-- Perbandingan Bulan Lalu -->
            <div class="border-t border-green-400 pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-green-100">vs Bulan Lalu:</span>
                    <div class="flex items-center space-x-2">
                        @if($pertumbuhan >= 0)
                            <i class="fas fa-arrow-up text-lg"></i>
                            <span class="text-lg font-bold">+{{ number_format($pertumbuhan, 1) }}%</span>
                        @else
                            <i class="fas fa-arrow-down text-lg"></i>
                            <span class="text-lg font-bold">{{ number_format($pertumbuhan, 1) }}%</span>
                        @endif
                    </div>
                </div>
                <p class="text-xs text-green-200 mt-2">Bulan Lalu: Rp {{ number_format($bulanLalu, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Breakdown Metode Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-credit-card text-red-600 mr-2"></i>Metode Pembayaran
            </h3>
            <canvas id="grafikMetode" height="200"></canvas>
            <div class="mt-4 space-y-2">
                @foreach($perMetode as $metode)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-2">
                        @if($metode->metode_pembayaran == 'tunai')
                            <span class="text-2xl">💵</span>
                        @elseif($metode->metode_pembayaran == 'kredit')
                            <span class="text-2xl">💳</span>
                        @elseif($metode->metode_pembayaran == 'debit')
                            <span class="text-2xl">💳</span>
                        @else
                            <span class="text-2xl">📱</span>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $metode->metode_pembayaran) }}</p>
                            <p class="text-xs text-gray-500">{{ $metode->jumlah }} transaksi</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">Rp {{ number_format($metode->total, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ number_format(($metode->total / $totalPendapatan) * 100, 1) }}%</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Grafik 12 Bulan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-red-600 mr-2"></i>Trend Pendapatan 12 Bulan Terakhir
        </h2>
        <canvas id="grafikBulanan" height="80"></canvas>
    </div>

    <!-- Detail Keuangan -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-table text-red-600 mr-2"></i>Ringkasan Keuangan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Laba Bersih</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Asumsi 100% laba</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Margin Keuntungan</p>
                <p class="text-2xl font-bold text-purple-600">100%</p>
                <p class="text-xs text-gray-500 mt-1">Tanpa perhitungan biaya</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Grafik Metode Pembayaran (Pie)
const ctxMetode = document.getElementById('grafikMetode').getContext('2d');
new Chart(ctxMetode, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($perMetode->pluck('metode_pembayaran')->map(function($m) { return ucfirst(str_replace('_', ' ', $m)); })) !!},
        datasets: [{
            data: {!! json_encode($perMetode->pluck('total')) !!},
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)',
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

// Grafik Bulanan (Bar Chart)
const ctxBulanan = document.getElementById('grafikBulanan').getContext('2d');
new Chart(ctxBulanan, {
    type: 'bar',
    data: {
        labels: {!! json_encode($grafikBulanan->map(function($item) {
            return \Carbon\Carbon::create()->year($item->tahun)->month($item->bulan)->format('M Y');
        })) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($grafikBulanan->pluck('total')) !!},
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        }
    }
});
</script>
@endsection