@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-red-600 mr-2"></i>Kelola Pengguna
            </h1>
            <p class="text-gray-600 mt-1">Manajemen pengguna sistem</p>
        </div>
        <a href="{{ route(role_route('pengguna.create')) }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center space-x-2">
            <i class="fas fa-user-plus"></i>
            <span>Tambah Pengguna</span>
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
        <!-- Total Pengguna -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pengguna</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $penggunas->total() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pemilik -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pemilik</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $penggunas->where('role', 'pemilik')->count() }}</h3>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Karyawan -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Karyawan</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $penggunas->where('role', 'karyawan')->count() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-user-tie text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Kasir -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Kasir</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $penggunas->where('role', 'kasir')->count() }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-cash-register text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form action="{{ route(role_route('pengguna.index')) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari Pengguna</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama atau username..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                </div>

                <!-- Filter Role -->
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                            <option value="">Semua Role</option>
                            <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                            <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route(role_route('pengguna.index')) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 text-sm">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">TTL</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($penggunas as $index => $pengguna)
                    <tr class="hover:bg-gray-50 transition duration-150 {{ $pengguna->id_pengguna === Auth::id() ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $penggunas->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                    @if($pengguna->role == 'pemilik') bg-yellow-100
                                    @elseif($pengguna->role == 'karyawan') bg-blue-100
                                    @else bg-green-100
                                    @endif">
                                    <i class="fas fa-user 
                                        @if($pengguna->role == 'pemilik') text-yellow-600
                                        @elseif($pengguna->role == 'karyawan') text-blue-600
                                        @else text-green-600
                                        @endif"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $pengguna->nama_lengkap }}</p>
                                    @if($pengguna->id_pengguna === Auth::id())
                                        <span class="text-xs text-blue-600 font-medium">(Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $pengguna->username }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($pengguna->tempat_lahir && $pengguna->tanggal_lahir)
                                {{ $pengguna->tempat_lahir }}, {{ $pengguna->tanggal_lahir->format('d M Y') }}
                                <br>
                                <span class="text-xs text-gray-400">({{ $pengguna->umur }} tahun)</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pengguna->role == 'pemilik')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-crown mr-1"></i>Pemilik
                                </span>
                            @elseif($pengguna->role == 'karyawan')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-tie mr-1"></i>Karyawan
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-cash-register mr-1"></i>Kasir
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $pengguna->create_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                @if($pengguna->id_pengguna !== Auth::id())
                                    <a href="{{ route(role_route('pengguna.edit'), $pengguna->id_pengguna) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <button onclick="confirmDelete({{ $pengguna->id_pengguna }})" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 inline-flex items-center text-xs">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                @endif
                            </div>
                            <form id="delete-form-{{ $pengguna->id_pengguna }}" 
                                  action="{{ route(role_route('pengguna.destroy'), $pengguna->id_pengguna) }}" 
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
                                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada pengguna</p>
                                <p class="text-sm mt-2">Klik tombol "Tambah Pengguna" untuk menambahkan pengguna baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($penggunas->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $penggunas->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript -->
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?\n\nPeringatan: Pengguna dengan riwayat transaksi tidak dapat dihapus!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection