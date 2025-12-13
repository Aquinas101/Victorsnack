@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-pie text-red-600 mr-2"></i>Analisis Bisnis
        </h1>
        <p class="text-gray-600 mt-1">Insight produk terlaris, kategori populer & pola penjualan</p>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form action="{{ route('admin.laporan.analisis') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Produk Terjual</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalProdukTerjual) }} pcs</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-box-open text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Produk Terlaris -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-fire mr-2"></i>Top 10 Produk Terlaris
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($produkTerlaris as $index => $produk)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full 
                                {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-gray-100 text-gray-600' : ($index == 2 ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600')) }}">
                                <span class="font-bold text-lg">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $produk->nama_produk }}</p>
                                <p class="text-xs text-gray-500">{{ $produk->kategori }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ $produk->total_terjual }} terjual</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kategori Terpopuler -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-orange-700">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-tags mr-2"></i>Kategori Terpopuler
                </h2>
            </div>
            <div class="p-6">
                @php
                    $maxPendapatan = $kategoriTerpopuler->max('total_pendapatan') ?: 1;
                @endphp
                <div class="space-y-4">
                    @forelse($kategoriTerpopuler as $kategori)
                        @php
                            $persentase = ($kategori->total_pendapatan / $maxPendapatan) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <span class="font-bold text-gray-800">{{ ucfirst($kategori->kategori) }}</span>
                                    <span class="text-xs text-gray-500 ml-2">({{ $kategori->jumlah_produk }} produk)</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $kategori->total_terjual }} terjual</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-4 rounded-full transition-all duration-300 flex items-center justify-end pr-2" 
                                     style="width: {{ $persentase }}%">
                                    <span class="text-xs text-white font-semibold">{{ number_format($persentase, 0) }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-green-600">Rp {{ number_format($kategori->total_pendapatan, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Varian Terpopuler -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-layer-group mr-2"></i>Top 10 Varian Terpopuler
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Produk</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Berat</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Terjual</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($varianTerpopuler as $index => $varian)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-800">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $varian->nama_produk }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                                {{ $varian->berat }}g
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-600">
                            Rp {{ number_format($varian->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                {{ $varian->total_terjual }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">
                            Rp {{ number_format($varian->total_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Jam Tersibuk -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-pink-600 to-pink-700">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-clock mr-2"></i>Jam Tersibuk
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($jamTersibuk->take(10) as $jam)
                        @php
                            $maxJam = $jamTersibuk->max('jumlah_transaksi') ?: 1;
                            $persentase = ($jam->jumlah_transaksi / $maxJam) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-gray-800">{{ str_pad($jam->jam, 2, '0', STR_PAD_LEFT) }}:00</span>
                                <span class="text-sm text-gray-600">{{ $jam->jumlah_transaksi }} transaksi</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-pink-500 to-pink-600 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Hari Tersibuk -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-calendar-week mr-2"></i>Hari Tersibuk
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($hariTersibuk as $hari)
                        @php
                            $maxHari = $hariTersibuk->max('jumlah_transaksi') ?: 1;
                            $persentase = ($hari->jumlah_transaksi / $maxHari) * 100;
                            $hariIndo = [
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu'
                            ];
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-gray-800">{{ $hariIndo[$hari->hari] ?? $hari->hari }}</span>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $hari->jumlah_transaksi }} transaksi</p>
                                    <p class="text-xs text-green-600 font-semibold">Rp {{ number_format($hari->total_pendapatan / 1000, 0) }}k</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection