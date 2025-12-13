@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-layer-group text-red-600 mr-2"></i>Varian Produk
            </h1>
            <p class="text-gray-600 mt-1">Kelola berat dan harga per produk</p>
        </div>
        <a href="{{ route(role_route('varian.create')) }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Varian</span>
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

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Search Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()"
                       placeholder="Cari produk atau berat..." 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="varianTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Berat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white" id="tableBody">
                    @forelse($varians as $index => $varian)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $varians->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($varian->produk->gambar)
                                    <img src="{{ asset($varian->produk->gambar) }}" 
                                        alt="{{ $varian->produk->nama_produk }}" 
                                        class="w-10 h-10 object-cover rounded-lg shadow-sm mr-3">
                                @else
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-cookie-bite text-red-600"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-semibold text-gray-900">{{ $varian->produk->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $varian->produk->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-weight mr-1"></i>
                                {{ $varian->berat }} gram
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            Rp {{ number_format($varian->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route(role_route('varian.edit'), $varian->id_varian) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <button onclick="confirmDelete({{ $varian->id_varian }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-trash mr-1"></i>
                                    <span class="text-xs">Hapus</span>
                                </button>
                            </div>
                            <form id="delete-form-{{ $varian->id_varian }}" 
                                  action="{{ route(role_route('varian.destroy'), $varian->id_varian) }}" 
                                  method="POST" 
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada varian produk</p>
                                <p class="text-sm mt-2">Klik tombol "Tambah Varian" untuk menambahkan varian produk baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($varians->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $varians->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript untuk Search dan Delete Confirmation -->
<script>
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('varianTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdName = tr[i].getElementsByTagName('td')[1];
        const tdBerat = tr[i].getElementsByTagName('td')[3];
        
        if (tdName || tdBerat) {
            const nameValue = tdName ? (tdName.textContent || tdName.innerText) : '';
            const beratValue = tdBerat ? (tdBerat.textContent || tdBerat.innerText) : '';
            
            if (nameValue.toLowerCase().indexOf(filter) > -1 || beratValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus varian ini?\n\nPeringatan: Varian yang sudah digunakan dalam transaksi tidak bisa dihapus!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection