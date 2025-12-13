@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header with Export Buttons -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-chart-line text-red-600 mr-2"></i>Laporan Penjualan
            </h1>
            <p class="text-gray-600 mt-1">Laporan detail transaksi penjualan dengan filter periode</p>
        </div>
        
        <!-- Export Buttons -->
        <div class="flex gap-2 no-print">
            <form action="{{ route('admin.laporan.penjualan.pdf') }}" method="GET" class="inline">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                @if(request('kasir'))
                    <input type="hidden" name="kasir" value="{{ request('kasir') }}">
                @endif
                @if(request('metode'))
                    <input type="hidden" name="metode" value="{{ request('metode') }}">
                @endif
                
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center gap-2 font-semibold">
                    <i class="fas fa-file-pdf text-xl"></i>
                    <span>Download PDF</span>
                </button>
            </form>
            
            <button onclick="window.print()" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center gap-2 font-semibold">
                <i class="fas fa-print text-xl"></i>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTransaksi) }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Item Terjual -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Item Terjual</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalItem) }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata per Transaksi -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Rata-rata / Transaksi</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-calculator text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Grafik Penjualan Harian
        </h2>
        <div class="overflow-x-auto">
            <div class="min-w-full flex space-x-2">
                @php
                    $maxPendapatan = $grafikHarian->max('total_pendapatan') ?: 1;
                @endphp
                @forelse($grafikHarian as $hari)
                    <div class="flex-1 flex flex-col items-center min-w-[60px]">
                        <div class="w-full bg-gray-200 rounded-t-lg overflow-hidden" style="height: 150px; display: flex; flex-direction: column-reverse;">
                            <div class="bg-gradient-to-t from-blue-600 to-blue-400 w-full transition-all duration-300" 
                                 style="height: {{ ($hari->total_pendapatan / $maxPendapatan * 100) }}%;">
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mt-2 font-medium">{{ \Carbon\Carbon::parse($hari->tanggal)->format('d/m') }}</p>
                        <p class="text-xs text-green-600 font-semibold">{{ number_format($hari->total_pendapatan / 1000, 0) }}k</p>
                        <p class="text-xs text-gray-500">{{ $hari->jumlah_transaksi }} trx</p>
                    </div>
                @empty
                    <div class="w-full text-center py-8 text-gray-500">
                        <i class="fas fa-chart-bar text-4xl mb-2"></i>
                        <p>Tidak ada data untuk ditampilkan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filter & Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50 no-print">
            <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                </div>

                <!-- Tanggal Akhir -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                </div>

                <!-- Filter Kasir -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kasir</label>
                    <select name="kasir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                        <option value="">Semua Kasir</option>
                        @foreach($kasirs as $kasir)
                            <option value="{{ $kasir->id_pengguna }}" {{ request('kasir') == $kasir->id_pengguna ? 'selected' : '' }}>
                                {{ $kasir->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Metode -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="metode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="kredit" {{ request('metode') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                        <option value="debit" {{ request('metode') == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="dompet_digital" {{ request('metode') == 'dompet_digital' ? 'selected' : '' }}>E-Wallet</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.penjualan') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 text-sm">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Kasir</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Metode</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-bold">#{{ $transaksi->id_transaksi }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $transaksi->tanggal_transaksi->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $transaksi->pengguna->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                {{ $transaksi->totalItem }} item
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($transaksi->metode_pembayaran == 'tunai')
                                <span class="text-green-600"><i class="fas fa-money-bill-wave mr-1"></i>Tunai</span>
                            @elseif($transaksi->metode_pembayaran == 'kredit')
                                <span class="text-blue-600"><i class="fas fa-credit-card mr-1"></i>Kredit</span>
                            @elseif($transaksi->metode_pembayaran == 'debit')
                                <span class="text-purple-600"><i class="fas fa-credit-card mr-1"></i>Debit</span>
                            @else
                                <span class="text-orange-600"><i class="fas fa-wallet mr-1"></i>E-Wallet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center no-print">
                            <a href="{{ route('admin.transaksi.show', $transaksi->id_transaksi) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksis->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 no-print">
            {{ $transaksis->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .container {
        max-width: 100%;
    }
}
</style>
@endsection