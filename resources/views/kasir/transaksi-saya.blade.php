@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-receipt text-red-600 mr-2"></i>Transaksi Saya
                </h1>
                <p class="text-gray-600 mt-1">Riwayat transaksi yang saya input</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('kasir.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-cash-register mr-2"></i>Ke Kasir
                </a>
                <a href="{{ route('kasir.riwayat.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-history mr-2"></i>Riwayat Lengkap
                </a>
            </div>
        </div>
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

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    @php
        // SEMUA transaksi user (tidak dibatasi hari ini saja)
        $semuaTransaksi = $transaksis;
        $hariIni = $transaksis->where('tanggal_transaksi', '>=', now()->startOfDay());
        
        $totalItemHariIni = $hariIni->sum(function($t) { 
            return $t->details->sum('jumlah'); 
        });
        $totalPenjualanHariIni = $hariIni->where('status_transaksi', 'berhasil')->sum('total_harga');
        $transaksiPendingHariIni = $hariIni->where('status_transaksi', 'pending')->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Transaksi Hari Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Transaksi Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $hariIni->count() }}</h3>
                    <p class="text-blue-100 text-xs mt-1">
                        Berhasil: {{ $hariIni->where('status_transaksi', 'berhasil')->count() }}
                    </p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Item Terjual -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Item Terjual Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalItemHariIni }}</h3>
                    <p class="text-purple-100 text-xs mt-1">Total item</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Penjualan Hari Ini</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
                    <p class="text-green-100 text-xs mt-1">Transaksi berhasil</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Hari Ini -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Transaksi Pending</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $transaksiPendingHariIni }}</h3>
                    <p class="text-orange-100 text-xs mt-1">Menunggu pembayaran</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-blue-600 mr-2"></i>Ringkasan Semua Transaksi
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                // FIX: Hitung dari SEMUA transaksi, bukan hanya hari ini
                $metodeCounts = [
                    'tunai' => $semuaTransaksi->where('metode_pembayaran', 'tunai')->count(),
                    'dompet_digital' => $semuaTransaksi->where('metode_pembayaran', 'dompet_digital')->count(),
                    'kredit' => $semuaTransaksi->where('metode_pembayaran', 'kredit')->count(),
                    'debit' => $semuaTransaksi->where('metode_pembayaran', 'debit')->count(),
                ];
                $totalMetode = array_sum($metodeCounts);
                
                // Total penjualan semua transaksi berhasil
                $totalPenjualanSemua = $semuaTransaksi->where('status_transaksi', 'berhasil')->sum('total_harga');
                $countBerhasil = $semuaTransaksi->where('status_transaksi', 'berhasil')->count();
            @endphp

            <div class="text-center p-4 bg-green-50 rounded-lg">
                <i class="fas fa-money-bill-wave text-2xl text-green-600 mb-2"></i>
                <p class="text-xs text-gray-600">Tunai</p>
                <p class="text-xl font-bold text-gray-800">{{ $metodeCounts['tunai'] }}</p>
                <p class="text-xs text-gray-500">{{ $totalMetode > 0 ? round(($metodeCounts['tunai']/$totalMetode)*100) : 0 }}%</p>
            </div>

            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <i class="fas fa-wallet text-2xl text-orange-600 mb-2"></i>
                <p class="text-xs text-gray-600">E-Wallet</p>
                <p class="text-xl font-bold text-gray-800">{{ $metodeCounts['dompet_digital'] }}</p>
                <p class="text-xs text-gray-500">{{ $totalMetode > 0 ? round(($metodeCounts['dompet_digital']/$totalMetode)*100) : 0 }}%</p>
            </div>

            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <i class="fas fa-credit-card text-2xl text-blue-600 mb-2"></i>
                <p class="text-xs text-gray-600">Kredit</p>
                <p class="text-xl font-bold text-gray-800">{{ $metodeCounts['kredit'] }}</p>
                <p class="text-xs text-gray-500">{{ $totalMetode > 0 ? round(($metodeCounts['kredit']/$totalMetode)*100) : 0 }}%</p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <i class="fas fa-credit-card text-2xl text-purple-600 mb-2"></i>
                <p class="text-xs text-gray-600">Debit</p>
                <p class="text-xl font-bold text-gray-800">{{ $metodeCounts['debit'] }}</p>
                <p class="text-xs text-gray-500">{{ $totalMetode > 0 ? round(($metodeCounts['debit']/$totalMetode)*100) : 0 }}%</p>
            </div>

            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <i class="fas fa-coins text-2xl text-gray-600 mb-2"></i>
                <p class="text-xs text-gray-600">Rata-rata</p>
                <p class="text-lg font-bold text-gray-800">
                    Rp {{ $countBerhasil > 0 ? number_format($totalPenjualanSemua / $countBerhasil, 0, ',', '.') : '0' }}
                </p>
                <p class="text-xs text-gray-500">per transaksi</p>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Search & Filter Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" onkeyup="searchTable()"
                           placeholder="Cari ID transaksi atau tanggal..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                </div>

                <!-- Filter Status -->
                <div>
                    <select id="statusFilter" onchange="filterStatus()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
                        <option value="">Semua Status</option>
                        <option value="berhasil">Berhasil</option>
                        <option value="pending">Pending</option>
                        <option value="gagal">Gagal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="transaksiTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 transition duration-150" data-status="{{ $transaksi->status_transaksi }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <span class="text-sm font-bold text-gray-900">#{{ $transaksi->id_transaksi }}</span>
                                @if($transaksi->order_id_midtrans)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-qrcode mr-1"></i>{{ substr($transaksi->order_id_midtrans, 0, 15) }}...
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-700 font-medium">
                                    <i class="far fa-calendar mr-1 text-blue-500"></i>
                                    {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                </span>
                                <span class="text-gray-500 text-xs mt-1">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $transaksi->tanggal_transaksi->format('H:i') }} WIB
                                    <span class="text-gray-400">({{ $transaksi->tanggal_transaksi->diffForHumans() }})</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 w-fit">
                                    <i class="fas fa-shopping-bag mr-1"></i>
                                    {{ $transaksi->details->sum('jumlah') }} item
                                </span>
                                <span class="text-xs text-gray-500 mt-1">
                                    {{ $transaksi->details->count() }} jenis produk
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </span>
                                @if($transaksi->metode_pembayaran == 'tunai')
                                    @if($transaksi->uang_dibayar)
                                        <span class="text-xs text-gray-500 mt-1">
                                            Bayar: Rp {{ number_format($transaksi->uang_dibayar, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    @if($transaksi->kembalian)
                                        <span class="text-xs text-blue-600">
                                            Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}
                                        </span>
                                    @endif
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
                                <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-semibold text-gray-600">Belum Ada Transaksi</p>
                                <p class="text-sm text-gray-500 mt-2">Transaksi akan muncul setelah Anda melakukan penjualan di kasir</p>
                                <a href="{{ route('kasir.index') }}" class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-cash-register mr-2"></i>Mulai Transaksi
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
            {{ $transaksis->links() }}
        </div>
        @endif
    </div>

    <!-- Info Footer -->
    @if($transaksis->count() > 0)
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Informasi</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Halaman ini menampilkan transaksi yang Anda input sendiri</li>
                    <li>Transaksi dengan status <span class="font-semibold">pending</span> menunggu konfirmasi pembayaran dari Midtrans</li>
                    <li>Anda dapat mencetak struk untuk transaksi yang sudah <span class="font-semibold">berhasil</span></li>
                    <li>Untuk melihat riwayat lengkap semua transaksi, klik tombol "Riwayat Lengkap" di atas</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
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
        const tdDate = tr[i].getElementsByTagName('td')[1];
        
        if (tdId && tdDate) {
            const idValue = tdId.textContent || tdId.innerText;
            const dateValue = tdDate.textContent || tdDate.innerText;
            
            if (idValue.toLowerCase().indexOf(filter) > -1 || dateValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

function filterStatus() {
    const select = document.getElementById('statusFilter');
    const filter = select.value.toLowerCase();
    const table = document.getElementById('transaksiTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const status = tr[i].getAttribute('data-status');
        
        if (filter === '' || status === filter) {
            tr[i].style.display = '';
        } else {
            tr[i].style.display = 'none';
        }
    }
}
</script>

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