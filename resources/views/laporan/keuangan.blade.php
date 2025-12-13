@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-money-bill-wave text-red-600 mr-2"></i>Laporan Keuangan
        </h1>
        <p class="text-gray-600 mt-1">Ringkasan keuangan dan performa kasir</p>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            <div class="md:col-span-1 flex items-end">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
            <p class="text-green-100 text-sm font-medium">Total Pendapatan</p>
            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>

        <!-- Transaksi Berhasil -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <p class="text-blue-100 text-sm font-medium">Transaksi Berhasil</p>
            <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTransaksiBerhasil) }}</h3>
        </div>

        <!-- Transaksi Pending -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <p class="text-yellow-100 text-sm font-medium">Transaksi Pending</p>
            <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTransaksiPending) }}</h3>
        </div>

        <!-- Transaksi Gagal -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
            <p class="text-red-100 text-sm font-medium">Transaksi Gagal</p>
            <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTransaksiGagal) }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Per Metode Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-credit-card text-blue-600 mr-2"></i>Breakdown Metode Pembayaran
            </h2>
            <div class="space-y-4">
                @forelse($perMetodePembayaran as $metode)
                    @php
                        $persentase = $totalPendapatan > 0 ? ($metode->total_pendapatan / $totalPendapatan * 100) : 0;
                        $icon = match($metode->metode_pembayaran) {
                            'tunai' => 'fa-money-bill-wave',
                            'kredit' => 'fa-credit-card',
                            'debit' => 'fa-credit-card',
                            default => 'fa-wallet'
                        };
                        $color = match($metode->metode_pembayaran) {
                            'tunai' => 'green',
                            'kredit' => 'blue',
                            'debit' => 'purple',
                            default => 'orange'
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center">
                                <i class="fas {{ $icon }} text-{{ $color }}-600 mr-2"></i>
                                <span class="font-semibold capitalize">{{ ucfirst($metode->metode_pembayaran) }}</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $metode->jumlah_transaksi }} transaksi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1">
                            <div class="bg-{{ $color }}-600 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $persentase }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ number_format($persentase, 1) }}%</span>
                            <span class="font-bold text-{{ $color }}-600">Rp {{ number_format($metode->total_pendapatan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Grafik Bulanan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-area text-purple-600 mr-2"></i>Pendapatan 6 Bulan Terakhir
            </h2>
            <div class="flex items-end space-x-2 h-64">
                @php
                    $maxPendapatan = $grafikBulanan->max('total_pendapatan') ?: 1;
                @endphp
                @forelse($grafikBulanan as $bulan)
                    <div class="flex-1 flex flex-col items-center justify-end">
                        <span class="text-xs font-semibold text-green-600 mb-1">
                            {{ number_format($bulan->total_pendapatan / 1000000, 1) }}jt
                        </span>
                        <div class="w-full bg-gradient-to-t from-purple-600 to-purple-400 rounded-t-lg transition-all duration-300" 
                             style="height: {{ ($bulan->total_pendapatan / $maxPendapatan * 100) }}%;">
                        </div>
                        <span class="text-xs text-gray-600 mt-2">
                            {{ \Carbon\Carbon::parse($bulan->bulan . '-01')->format('M') }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Performa Kasir -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-700">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-users mr-2"></i>Performa Kasir
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Ranking</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nama Kasir</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Transaksi</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase">Total Pendapatan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($perKasir as $index => $kasir)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($index == 0)
                                <span class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                                    <i class="fas fa-trophy text-yellow-600"></i>
                                </span>
                            @elseif($index == 1)
                                <span class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                                    <i class="fas fa-medal text-gray-600"></i>
                                </span>
                            @elseif($index == 2)
                                <span class="flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full">
                                    <i class="fas fa-award text-orange-600"></i>
                                </span>
                            @else
                                <span class="text-gray-600 font-semibold">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $kasir->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                {{ $kasir->jumlah_transaksi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">
                            Rp {{ number_format($kasir->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-600">
                            Rp {{ number_format($kasir->total_pendapatan / $kasir->jumlah_transaksi, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-user-slash text-4xl mb-2"></i>
                            <p>Tidak ada data kasir</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection