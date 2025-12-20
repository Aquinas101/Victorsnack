@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-history text-red-600 mr-2"></i>Riwayat Pemesanan
        </h1>
        <p class="text-gray-600 mt-1">History lengkap transaksi yang saya lakukan</p>
    </div>

    <!-- Statistik Performa -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Transaksi All Time -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->total() }}</h3>
                    <p class="text-blue-100 text-xs mt-1">All Time</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Penjualan</p>
                    @php
                        $totalPenjualan = \App\Models\Transaksi::where('id_pengguna', Auth::id())
                                                               ->where('status_transaksi', 'berhasil')
                                                               ->sum('total_harga');
                    @endphp
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                    <p class="text-green-100 text-xs mt-1">Transaksi Berhasil</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata Transaksi -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Rata-rata / Transaksi</p>
                    @php
                        $countBerhasil = \App\Models\Transaksi::where('id_pengguna', Auth::id())
                                                              ->where('status_transaksi', 'berhasil')
                                                              ->count();
                    @endphp
                    <h3 class="text-2xl font-bold mt-2">
                        @if($countBerhasil > 0)
                            Rp {{ number_format($totalPenjualan / $countBerhasil, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </h3>
                    <p class="text-purple-100 text-xs mt-1">Per Transaksi</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Hari Ini -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Transaksi Hari Ini</p>
                    @php
                        $hariIni = \App\Models\Transaksi::where('id_pengguna', Auth::id())
                                                        ->whereDate('tanggal_transaksi', today())
                                                        ->where('status_transaksi', 'berhasil');
                        $totalHariIni = $hariIni->sum('total_harga');
                        $countHariIni = $hariIni->count();
                    @endphp
                    <h3 class="text-xl font-bold mt-2">{{ $countHariIni }} Transaksi</h3>
                    <p class="text-orange-100 text-xs mt-1">
                        Rp {{ number_format($totalHariIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Performa 7 Hari Terakhir -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Performa 7 Hari Terakhir
        </h2>
        <div class="grid grid-cols-7 gap-2">
            @php
                $last7Days = collect();
                for($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dayTotal = \App\Models\Transaksi::where('id_pengguna', Auth::id())
                                                     ->whereDate('tanggal_transaksi', $date->toDateString())
                                                     ->where('status_transaksi', 'berhasil')
                                                     ->sum('total_harga');
                    $dayCount = \App\Models\Transaksi::where('id_pengguna', Auth::id())
                                                     ->whereDate('tanggal_transaksi', $date->toDateString())
                                                     ->where('status_transaksi', 'berhasil')
                                                     ->count();
                    $last7Days->push([
                        'date' => $date,
                        'total' => $dayTotal,
                        'count' => $dayCount
                    ]);
                }
                $maxTotal = $last7Days->max('total') ?: 1;
            @endphp

            @foreach($last7Days as $day)
                <div class="flex flex-col items-center">
                    <div class="w-full bg-gray-200 rounded-lg overflow-hidden relative" style="height: 120px;">
                        <div class="bg-gradient-to-t from-blue-500 to-blue-400 w-full absolute bottom-0 transition-all duration-300 rounded-t-lg" 
                             style="height: {{ $maxTotal > 0 ? ($day['total'] / $maxTotal * 100) : 0 }}%;">
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 mt-2 font-bold">{{ $day['date']->format('D') }}</p>
                    <p class="text-xs text-gray-500">{{ $day['date']->format('d/m') }}</p>
                    <p class="text-xs text-blue-600 font-semibold mt-1">
                        {{ $day['count'] }} trx
                    </p>
                    <p class="text-xs text-green-600 font-bold">
                        @if($day['total'] > 0)
                            @if($day['total'] >= 1000000)
                                {{ number_format($day['total'] / 1000000, 1) }}jt
                            @else
                                {{ number_format($day['total'] / 1000, 0) }}k
                            @endif
                        @else
                            -
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filter & Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form action="{{ route('kasir.riwayat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="ID Transaksi atau tanggal..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>

                <!-- Periode -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">Semua Waktu</option>
                        <option value="7" {{ request('periode') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('periode') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ request('periode') == '90' ? 'selected' : '' }}>90 Hari Terakhir</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">Semua Status</option>
                        <option value="berhasil" {{ request('status') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('kasir.riwayat.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 text-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <span class="text-sm font-bold text-gray-900">#{{ $transaksi->id_transaksi }}</span>
                                @if($transaksi->order_id_midtrans)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-credit-card mr-1"></i>{{ substr($transaksi->order_id_midtrans, 0, 20) }}...
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span class="font-medium">
                                    <i class="far fa-calendar mr-1 text-blue-500"></i>
                                    {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                </span>
                                <span class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $transaksi->tanggal_transaksi->format('H:i') }} WIB
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-shopping-bag mr-1"></i>
                                {{ $transaksi->details->sum('jumlah') }} item
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </span>
                                @if($transaksi->metode_pembayaran == 'tunai' && $transaksi->kembalian)
                                    <span class="text-xs text-gray-500 mt-1">
                                        Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaksi->metode_pembayaran == 'tunai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-money-bill-wave mr-1"></i>Tunai
                                </span>
                            @elseif($transaksi->metode_pembayaran == 'kredit')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-credit-card mr-1"></i>Kredit
                                </span>
                            @elseif($transaksi->metode_pembayaran == 'debit')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-credit-card mr-1"></i>Debit
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-wallet mr-1"></i>E-Wallet
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaksi->status_transaksi == 'berhasil')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Berhasil
                                </span>
                            @elseif($transaksi->status_transaksi == 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kasir.transaksi.detail', $transaksi->id_transaksi) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs font-medium"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                @if($transaksi->status_transaksi == 'berhasil')
                                    <a href="{{ route('kasir.transaksi.cetak', $transaksi->id_transaksi) }}" 
                                       target="_blank"
                                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs font-medium"
                                       title="Cetak Struk">
                                        <i class="fas fa-print mr-1"></i>Cetak
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-semibold text-gray-600">Belum Ada Riwayat Transaksi</p>
                                <p class="text-sm text-gray-500 mt-2">Riwayat transaksi Anda akan muncul di sini</p>
                                <a href="{{ route('kasir.index') }}" class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-plus-circle mr-2"></i>Mulai Transaksi Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksis->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $transaksis->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Footer -->
    @if($transaksis->count() > 0)
    <div class="mt-6 bg-gradient-to-r from-gray-700 to-gray-800 rounded-xl shadow-lg p-6 text-white">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
            <div>
                <p class="text-gray-300 text-sm">Halaman Ini</p>
                <p class="text-2xl font-bold mt-1">{{ $transaksis->count() }}</p>
                <p class="text-gray-400 text-xs">Transaksi</p>
            </div>
            <div>
                <p class="text-gray-300 text-sm">Total Item</p>
                <p class="text-2xl font-bold mt-1">{{ $transaksis->sum(function($t) { return $t->details->sum('jumlah'); }) }}</p>
                <p class="text-gray-400 text-xs">Item Terjual</p>
            </div>
            <div>
                <p class="text-gray-300 text-sm">Total Nilai</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($transaksis->where('status_transaksi', 'berhasil')->sum('total_harga'), 0, ',', '.') }}</p>
                <p class="text-gray-400 text-xs">Halaman Ini</p>
            </div>
            <div>
                <p class="text-gray-300 text-sm">Status Berhasil</p>
                <p class="text-2xl font-bold mt-1">{{ $transaksis->where('status_transaksi', 'berhasil')->count() }}</p>
                <p class="text-gray-400 text-xs">dari {{ $transaksis->count() }} transaksi</p>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Custom Scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection