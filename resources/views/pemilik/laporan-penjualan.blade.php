@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line text-red-600 mr-2"></i>Laporan Penjualan
        </h1>
        <p class="text-gray-600 mt-1">Analisis penjualan dan tren bisnis</p>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form action="{{ route('laporan.penjualan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                <select name="periode" onchange="toggleCustomDate(this)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    <option value="hari_ini" {{ $periode == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu_ini" {{ $periode == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ $periode == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ $periode == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            
            <div id="customDate" class="md:col-span-2 grid grid-cols-2 gap-4" style="display: {{ $periode == 'custom' ? 'grid' : 'none' }};">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai</label>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Penjualan</p>
                    <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $totalTransaksi }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Rata-rata Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($rataRata, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                    <i class="fas fa-chart-bar text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan Harian -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-red-600 mr-2"></i>Grafik Penjualan 7 Hari Terakhir
        </h2>
        <canvas id="grafikHarian" height="80"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 10 Produk -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy text-red-600 mr-2"></i>Top 10 Produk Terlaris
            </h2>
            <div class="space-y-3">
                @forelse($topProduk as $index => $item)
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:shadow-md transition">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full 
                        {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-gray-100 text-gray-600' : ($index == 2 ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600')) }}">
                        <span class="font-bold text-lg">{{ $index + 1 }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $item->varian->produk->nama_produk }}</p>
                        <p class="text-xs text-gray-500">{{ $item->varian->berat }}g - Terjual: {{ $item->total_terjual }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Penjualan per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-tags text-red-600 mr-2"></i>Penjualan per Kategori
            </h2>
            <canvas id="grafikKategori" height="200"></canvas>
            <div class="mt-4 space-y-2">
                @foreach($perKategori as $kat)
                <div class="flex justify-between items-center p-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">{{ $kat->kategori }}</span>
                    <span class="text-sm font-bold text-green-600">Rp {{ number_format($kat->total, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Toggle custom date
function toggleCustomDate(select) {
    const customDate = document.getElementById('customDate');
    customDate.style.display = select.value === 'custom' ? 'grid' : 'none';
}

// Grafik Harian
const ctxHarian = document.getElementById('grafikHarian').getContext('2d');
new Chart(ctxHarian, {
    type: 'line',
    data: {
        labels: {!! json_encode($grafikHarian->pluck('tanggal')->map(function($t) { return \Carbon\Carbon::parse($t)->format('d M'); })) !!},
        datasets: [{
            label: 'Penjualan',
            data: {!! json_encode($grafikHarian->pluck('total')) !!},
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
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