@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-shopping-cart text-red-600 mr-2"></i>Data Transaksi
        </h1>
        <p class="text-gray-600 mt-1">Kelola semua transaksi penjualan</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->total() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Transaksi Berhasil -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Berhasil</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->where('status_transaksi', 'berhasil')->count() }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Transaksi Pending -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksis->where('status_transaksi', 'pending')->count() }}</h3>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($transaksis->where('status_transaksi', 'berhasil')->sum('total_harga'), 0, ',', '.') }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form action="{{ route(role_route('transaksi.index')) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter Tanggal -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">Semua Status</option>
                        <option value="berhasil" {{ request('status') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <!-- Filter Metode -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="metode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="kredit" {{ request('metode') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                        <option value="debit" {{ request('metode') == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="dompet_digital" {{ request('metode') == 'dompet_digital' ? 'selected' : '' }}>Dompet Digital</option>
                    </select>
                </div>

                <!-- Tombol Filter -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route(role_route('transaksi.index')) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 text-sm">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">ID Transaksi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kasir</th>
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
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $transaksi->namaKasir }}</span>
                            </div>
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
                                <a href="{{ route(role_route('transaksi.show'), $transaksi->id_transaksi) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route(role_route('transaksi.cetak'), $transaksi->id_transaksi) }}" 
                                   target="_blank"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                    <i class="fas fa-print mr-1"></i>Cetak
                                </a>
                                @if(Auth::user()->role === 'pemilik')
                                <button onclick="confirmDelete({{ $transaksi->id_transaksi }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                                @endif
                            </div>
                            @if(Auth::user()->role === 'pemilik')
                            <form id="delete-form-{{ $transaksi->id_transaksi }}" 
                                  action="{{ route(role_route('transaksi.destroy'), $transaksi->id_transaksi) }}" 
                                  method="POST" 
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada transaksi</p>
                                <p class="text-sm mt-2">Transaksi akan muncul setelah kasir melakukan penjualan</p>
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
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?\n\nPeringatan: Stok akan dikembalikan!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection