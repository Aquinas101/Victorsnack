@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-5xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route(role_route('transaksi.index')) }}" class="hover:text-red-600 transition">
                <i class="fas fa-shopping-cart"></i> Data Transaksi
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Detail Transaksi</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-receipt text-red-600 mr-2"></i>Detail Transaksi
                </h1>
                <p class="text-gray-600 mt-1">ID Transaksi: <span class="font-bold">#{{ $transaksi->id_transaksi }}</span></p>
            </div>
            <a href="{{ route(role_route('transaksi.cetak'), $transaksi->id_transaksi) }}" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
                <i class="fas fa-print"></i>
                <span>Cetak Struk</span>
            </a>
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

    <!-- Info Transaksi -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-2">Status Transaksi</p>
            @if($transaksi->status_transaksi == 'berhasil')
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>BERHASIL
                </span>
            @elseif($transaksi->status_transaksi == 'pending')
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-2"></i>PENDING
                </span>
            @else
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-2"></i>GAGAL
                </span>
            @endif
        </div>

        <!-- Card Tanggal -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-2">Tanggal & Waktu</p>
            <p class="text-xl font-bold text-gray-800">
                <i class="far fa-calendar text-blue-600 mr-2"></i>
                {{ $transaksi->tanggal_transaksi->format('d M Y') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">
                <i class="far fa-clock text-blue-600 mr-2"></i>
                {{ $transaksi->tanggal_transaksi->format('H:i') }} WIB
            </p>
        </div>

        <!-- Card Kasir -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-2">Kasir</p>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800">{{ $transaksi->namaKasir }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $transaksi->pengguna->role }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-list mr-2"></i>Detail Produk
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Varian</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transaksi->details as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cookie-bite text-red-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $detail->namaProduk }}</p>
                                    <p class="text-xs text-gray-500">{{ $detail->varian->produk->kategori }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $detail->beratVarian }} gram
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">
                            Rp {{ number_format($detail->varian->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-blue-100 text-blue-800">
                                {{ $detail->jumlah }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-green-600">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary & Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-calculator text-blue-600 mr-2"></i>Ringkasan
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Total Item</span>
                    <span class="text-sm font-bold text-gray-900">{{ $transaksi->totalItem }} item</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Subtotal</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-lg font-bold text-gray-800">TOTAL</span>
                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-credit-card text-green-600 mr-2"></i>Pembayaran
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Metode Pembayaran</p>
                    <div class="bg-gray-50 rounded-lg p-3 border-2 border-gray-200">
                        @if($transaksi->metode_pembayaran == 'tunai')
                            <span class="text-lg font-bold text-green-600">
                                <i class="fas fa-money-bill-wave mr-2"></i>TUNAI
                            </span>
                        @elseif($transaksi->metode_pembayaran == 'kredit')
                            <span class="text-lg font-bold text-blue-600">
                                <i class="fas fa-credit-card mr-2"></i>KARTU KREDIT
                            </span>
                        @elseif($transaksi->metode_pembayaran == 'debit')
                            <span class="text-lg font-bold text-purple-600">
                                <i class="fas fa-credit-card mr-2"></i>KARTU DEBIT
                            </span>
                        @else
                            <span class="text-lg font-bold text-orange-600">
                                <i class="fas fa-wallet mr-2"></i>DOMPET DIGITAL
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Total Bayar</p>
                    <div class="bg-green-50 rounded-lg p-3 border-2 border-green-200">
                        <p class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route(role_route('transaksi.index')) }}" 
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Kembali</span>
        </a>
        
        @if(Auth::user()->role === 'pemilik')
        <button onclick="confirmDelete()" 
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 inline-flex items-center">
            <i class="fas fa-trash mr-2"></i>
            <span>Hapus Transaksi</span>
        </button>
        @endif
    </div>

    <!-- Form Delete Hidden -->
    @if(Auth::user()->role === 'pemilik')
    <form id="delete-form" action="{{ route(role_route('transaksi.destroy'), $transaksi->id_transaksi) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>

<!-- JavaScript -->
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?\n\nPeringatan: Stok akan dikembalikan!')) {
        document.getElementById('delete-form').submit();
    }
}

// Auto print on load if from success redirect
@if(session('auto_print'))
    window.onload = function() {
        window.open('{{ route(role_route("transaksi.cetak"), $transaksi->id_transaksi) }}', '_blank');
    }
@endif
</script>
@endsection
