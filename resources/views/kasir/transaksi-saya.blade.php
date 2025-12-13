@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-receipt text-red-600 mr-2"></i>Transaksi Saya
        </h1>
        <p class="text-gray-600 mt-1">Riwayat transaksi yang saya input</p>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Hari Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Transaksi Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->where('tanggal_transaksi', '>=', now()->startOfDay())->count() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->total() }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Item Terjual -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Item Terjual</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->sum(function($t) { return $t->totalItem; }) }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Penjualan</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</h3>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Search Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()"
                       placeholder="Cari ID transaksi..." 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="transaksiTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">#{{ $transaksi->id_transaksi }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                            <br>
                            <i class="far fa-clock mr-1"></i>
                            {{ $transaksi->tanggal_transaksi->format('H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $transaksi->totalItem }} item
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('kasir.transaksi.cetak', $transaksi->id_transaksi) }}" 
                                   target="_blank"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                    <i class="fas fa-print mr-1"></i>Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada transaksi</p>
                                <p class="text-sm mt-2">Transaksi akan muncul setelah Anda melakukan penjualan di kasir</p>
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
            {{ $transaksis->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript -->
<script>
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('transaksiTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdId = tr[i].getElementsByTagName('td')[0];
        
        if (tdId) {
            const idValue = tdId.textContent || tdId.innerText;
            
            if (idValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}
</script>
@endsection