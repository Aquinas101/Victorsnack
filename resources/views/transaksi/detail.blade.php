@extends('layouts.app')

@section('content')
@php
    $backRoute = Auth::user()->role === 'kasir'
        ? 'kasir.transaksi.saya'
        : role_route('transaksi.index');
@endphp

<div class="container mx-auto max-w-5xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route($backRoute) }}" class="hover:text-red-600 transition">
                <i class="fas fa-shopping-cart"></i> Data Transaksi
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Detail Transaksi</span>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-receipt text-red-600 mr-2"></i> Detail Transaksi
                </h1>
                <p class="text-gray-600 mt-1">
                    ID Transaksi:
                    <span class="font-bold">#{{ $transaksi->id_transaksi }}</span>
                </p>
            </div>

            <a href="{{ route(role_route('transaksi.cetak'), $transaksi->id_transaksi) }}"
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-lg transition flex items-center space-x-2">
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
        <!-- Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-2">Status Transaksi</p>
            @if($transaksi->status_transaksi === 'berhasil')
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i> BERHASIL
                </span>
            @elseif($transaksi->status_transaksi === 'pending')
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-2"></i> PENDING
                </span>
            @else
                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-2"></i> GAGAL
                </span>
            @endif
        </div>

        <!-- Tanggal -->
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

        <!-- Kasir -->
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
                <i class="fas fa-list mr-2"></i> Detail Produk
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Varian</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transaksi->details as $i => $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $detail->namaProduk }}</td>
                            <td class="px-6 py-4">{{ $detail->beratVarian }} gram</td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ number_format($detail->varian->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold">{{ $detail->jumlah }}</td>
                            <td class="px-6 py-4 text-right font-bold text-green-600">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route($backRoute) }}"
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>

        @if(Auth::user()->role === 'pemilik')
            <button onclick="confirmDelete()"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition inline-flex items-center">
                <i class="fas fa-trash mr-2"></i> Hapus Transaksi
            </button>
        @endif
    </div>

    @if(Auth::user()->role === 'pemilik')
        <form id="delete-form"
              action="{{ route(role_route('transaksi.destroy'), $transaksi->id_transaksi) }}"
              method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?\n\nStok akan dikembalikan!')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
