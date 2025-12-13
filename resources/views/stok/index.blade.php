@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-warehouse text-red-600 mr-2"></i>Stok Barang
            </h1>
            <p class="text-gray-600 mt-1">Kelola stok persediaan produk</p>
        </div>
        <a href="{{ route(role_route('stok.create')) }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Stok</span>
        </a>
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
        <!-- Total Produk -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Produk</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stoks->total() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Stok Aman (>= 5 kg) -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Stok Aman</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stoks->where('jumlah', '>=', 5)->count() }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Stok Menipis (< 5 kg) -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Stok Menipis</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stoks->where('jumlah', '<', 5)->where('jumlah', '>', 0)->count() }}</h3>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Stok Habis -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Stok Habis</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stoks->where('jumlah', 0)->count() }}</h3>
                </div>
                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
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
                       placeholder="Cari nama produk..." 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="stokTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Jumlah Stok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Terakhir Update</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white" id="tableBody">
                    @forelse($stoks as $index => $stok)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $stoks->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($stok->produk->gambar)
                                    <img src="{{ asset($stok->produk->gambar) }}" 
                                        alt="{{ $stok->produk->nama_produk }}" 
                                        class="w-10 h-10 object-cover rounded-lg shadow-sm mr-3">
                                @else
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-red-600"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-semibold text-gray-900">{{ $stok->produk->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $stok->produk->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($stok->jumlah == 0) bg-red-100 text-red-800
                                @elseif($stok->jumlah < 5) bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                <i class="fas fa-weight mr-1"></i>
                                {{ number_format($stok->jumlah, 2, ',', '.') }} {{ $stok->satuan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stok->jumlah == 0)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Habis
                                </span>
                            @elseif($stok->jumlah < 5)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Menipis
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <i class="far fa-clock mr-1"></i>
                            {{ $stok->update_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route(role_route('stok.edit'), $stok->id_stok) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <button onclick="confirmDelete({{ $stok->id_stok }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-trash mr-1"></i>
                                    <span class="text-xs">Hapus</span>
                                </button>
                            </div>
                            <form id="delete-form-{{ $stok->id_stok }}" 
                                  action="{{ route(role_route('stok.destroy'), $stok->id_stok) }}" 
                                  method="POST" 
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-warehouse text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada data stok</p>
                                <p class="text-sm mt-2">Klik tombol "Tambah Stok" untuk menambahkan stok produk</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($stoks->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $stoks->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript -->
<script>
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('stokTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdName = tr[i].getElementsByTagName('td')[1];
        
        if (tdName) {
            const nameValue = tdName.textContent || tdName.innerText;
            
            if (nameValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus stok ini?\n\nPeringatan: Data stok akan dihapus permanen!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection