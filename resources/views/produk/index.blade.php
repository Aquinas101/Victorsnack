@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-box text-red-600 mr-2"></i>Data Produk
            </h1>
            <p class="text-gray-600 mt-1">
                @if(Auth::user()->role === 'kasir')
                    Lihat daftar produk tersedia
                @else
                    Kelola semua produk snack
                @endif
            </p>
        </div>
        
        {{-- Tombol Tambah hanya untuk Pemilik dan Karyawan --}}
        @if(Auth::user()->role !== 'kasir')
        <a href="{{ route(role_route('produk.create')) }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </a>
        @endif
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

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Search Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()"
                       placeholder="Cari nama produk atau kategori..." 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="produkTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Diperbarui</th>
                        @if(Auth::user()->role !== 'kasir')
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white" id="tableBody">
                    @forelse($produks as $index => $produk)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $produks->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($produk->gambar)
                                <img src="{{ asset($produk->gambar) }}" 
                                     alt="{{ $produk->nama_produk }}" 
                                     class="w-16 h-16 object-cover rounded-lg shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-red-400 text-2xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-semibold text-gray-900">{{ $produk->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $produk->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $produk->create_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <i class="far fa-clock mr-1"></i>
                            {{ $produk->update_at->format('d M Y') }}
                        </td>
                        
                        {{-- Aksi hanya untuk Pemilik dan Karyawan --}}
                        @if(Auth::user()->role !== 'kasir')
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route(role_route('produk.edit'), $produk->id_produk) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <button onclick="confirmDelete({{ $produk->id_produk }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center">
                                    <i class="fas fa-trash mr-1"></i>
                                    <span class="text-xs">Hapus</span>
                                </button>
                            </div>
                            <form id="delete-form-{{ $produk->id_produk }}" 
                                  action="{{ route(role_route('produk.destroy'), $produk->id_produk) }}" 
                                  method="POST" 
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'kasir' ? '6' : '7' }}" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada data produk</p>
                                @if(Auth::user()->role !== 'kasir')
                                <p class="text-sm mt-2">Klik tombol "Tambah Produk" untuk menambahkan produk baru</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($produks->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $produks->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript untuk Search dan Delete Confirmation -->
<script>
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('produkTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdName = tr[i].getElementsByTagName('td')[2];
        const tdCategory = tr[i].getElementsByTagName('td')[3];
        
        if (tdName || tdCategory) {
            const nameValue = tdName.textContent || tdName.innerText;
            const categoryValue = tdCategory.textContent || tdCategory.innerText;
            
            if (nameValue.toLowerCase().indexOf(filter) > -1 || categoryValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?\n\nPeringatan: Menghapus produk akan menghapus semua varian dan stok yang terkait!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection