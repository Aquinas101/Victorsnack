@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Kasir</h1>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->nama_lengkap }}</p>
        <p class="text-sm text-gray-500 mt-1">
            <i class="far fa-calendar-alt mr-1"></i>
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    <!-- Statistik Hari Ini -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 text-white mb-8">
        <h2 class="text-lg font-semibold mb-4 flex items-center">
            <i class="fas fa-calendar-day mr-2"></i>
            Performa Hari Ini
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-100 mb-1">Transaksi Hari Ini</p>
                        <p class="text-3xl font-bold">{{ $transaksiHariIni }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-100 mb-1">Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $totalTransaksi }}</span>
            </div>
            <h3 class="text-lg font-semibold">Total Transaksi</h3>
            <p class="text-sm text-blue-100">Keseluruhan</p>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <span class="text-2xl font-bold">Rp {{ number_format($totalPendapatan / 1000, 0) }}K</span>
            </div>
            <h3 class="text-lg font-semibold">Total Pendapatan</h3>
            <p class="text-sm text-purple-100">Keseluruhan</p>
        </div>

        <!-- Produk Tersedia -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box-open text-2xl"></i>
                </div>
                <span class="text-3xl font-bold">{{ $produkTersedia }}</span>
            </div>
            <h3 class="text-lg font-semibold">Produk Tersedia</h3>
            <p class="text-sm text-orange-100">Stok tersedia</p>
        </div>
    </div>

    <!-- Grafik & Metode Pembayaran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Grafik 7 Hari Terakhir -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Penjualan 7 Hari Terakhir
            </h2>
            <div class="relative" style="height: 300px;">
                <canvas id="chartPenjualan7Hari"></canvas>
            </div>
        </div>

        <!-- Metode Pembayaran Hari Ini -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-credit-card text-green-600 mr-2"></i>
                Metode Pembayaran Hari Ini
            </h2>
            @if($pembayaranHariIni->count() > 0)
            <div class="space-y-3">
                @foreach($pembayaranHariIni as $metode)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        @if($metode->metode_pembayaran === 'tunai')
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                            </div>
                        @elseif($metode->metode_pembayaran === 'kredit')
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-blue-600"></i>
                            </div>
                        @elseif($metode->metode_pembayaran === 'debit')
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-purple-600"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-wallet text-orange-600"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $metode->metode_pembayaran)) }}</p>
                            <p class="text-sm text-gray-500">{{ $metode->jumlah }} transaksi</p>
                        </div>
                    </div>
                    <span class="text-green-600 font-bold">Rp {{ number_format($metode->total, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-credit-card text-5xl mb-3"></i>
                <p>Belum ada transaksi hari ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-purple-600 mr-2"></i>
                Transaksi Terbaru
            </h2>
            <a href="{{ route('kasir.transaksi.saya') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID Transaksi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Item</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Metode</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $transaksi)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm font-mono text-gray-800">
                            #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-semibold">
                            {{ $transaksi->details->count() }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($transaksi->status_transaksi === 'berhasil')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Berhasil
                                </span>
                            @elseif($transaksi->status_transaksi === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('kasir.transaksi.detail', $transaksi->id_transaksi) }}" 
                               class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('kasir.transaksi.cetak', $transaksi->id_transaksi) }}" 
                               class="ml-2 text-green-600 hover:text-green-800 font-semibold text-sm"
                               target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <i class="fas fa-receipt text-5xl mb-3"></i>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-gradient-to-r from-red-600 to-red-700 rounded-xl shadow-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-bolt mr-2"></i>
            Aksi Cepat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('kasir.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-cash-register text-2xl"></i>
                <span class="font-semibold">Buat Transaksi Baru</span>
            </a>
            <a href="{{ route('kasir.transaksi.saya') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-receipt text-2xl"></i>
                <span class="font-semibold">Transaksi Saya</span>
            </a>
            <a href="{{ route('kasir.produk.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition flex items-center space-x-3">
                <i class="fas fa-box-open text-2xl"></i>
                <span class="font-semibold">Lihat Produk</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartPenjualan7Hari');
    
    if (ctx) {
        const chartData = @json($penjualan7Hari);
        
        // Buat array 7 hari terakhir
        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toISOString().split('T')[0]);
        }

        // Map data ke 7 hari terakhir
        const mappedData = last7Days.map(date => {
            const found = chartData.find(item => item.tanggal === date);
            return {
                tanggal: date,
                jumlah: found ? parseInt(found.jumlah) : 0,
                total: found ? parseFloat(found.total) : 0
            };
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mappedData.map(item => {
                    const date = new Date(item.tanggal);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: mappedData.map(item => item.total),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    borderRadius: 8
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
                                const index = context.dataIndex;
                                const jumlah = mappedData[index].jumlah;
                                return [
                                    'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID'),
                                    'Transaksi: ' + jumlah
                                ];
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
    }
});
</script>
@endpush
@endsection