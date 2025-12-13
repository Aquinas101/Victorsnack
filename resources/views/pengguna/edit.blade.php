@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route(role_route('pengguna.index')) }}" class="hover:text-red-600 transition">
                <i class="fas fa-users"></i> Kelola Pengguna
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit Pengguna</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-edit text-red-600 mr-2"></i>Edit Pengguna
        </h1>
        <p class="text-gray-600 mt-1">Perbarui informasi pengguna: {{ $pengguna->nama_lengkap }}</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-pen mr-2"></i>Form Edit Pengguna
            </h2>
        </div>

        <form action="{{ route(role_route('pengguna.update'), $pengguna->id_pengguna) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-red-600 mr-1"></i>Nama Lengkap
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_lengkap" 
                       id="nama_lengkap" 
                       value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('nama_lengkap') border-red-500 @enderror" 
                       placeholder="Contoh: Budi Santoso"
                       required>
                @error('nama_lengkap')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tempat Lahir -->
                <div>
                    <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-red-600 mr-1"></i>Tempat Lahir
                    </label>
                    <input type="text" 
                           name="tempat_lahir" 
                           id="tempat_lahir" 
                           value="{{ old('tempat_lahir', $pengguna->tempat_lahir) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('tempat_lahir') border-red-500 @enderror" 
                           placeholder="Contoh: Jakarta">
                    @error('tempat_lahir')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-red-600 mr-1"></i>Tanggal Lahir
                    </label>
                    <input type="date" 
                           name="tanggal_lahir" 
                           id="tanggal_lahir" 
                           value="{{ old('tanggal_lahir', $pengguna->tanggal_lahir ? $pengguna->tanggal_lahir->format('Y-m-d') : '') }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-at text-red-600 mr-1"></i>Username
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       value="{{ old('username', $pengguna->username) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('username') border-red-500 @enderror" 
                       placeholder="Contoh: budi123"
                       required>
                @error('username')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password (Opsional) -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Update Password</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Kosongkan jika tidak ingin mengubah password. Isi hanya jika ingin mengganti password baru.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-red-600 mr-1"></i>Password Baru (Opsional)
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror" 
                           placeholder="Minimal 6 karakter">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-red-600 mr-1"></i>Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-user-tag text-red-600 mr-1"></i>Pilih Role
                    <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Pemilik -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition duration-200">
                        <input type="radio" name="role" value="pemilik" class="sr-only peer" {{ old('role', $pengguna->role) == 'pemilik' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-yellow-500 peer-checked:bg-yellow-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-crown text-3xl text-yellow-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Pemilik</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Akses penuh sistem</span>
                    </label>

                    <!-- Karyawan -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition duration-200">
                        <input type="radio" name="role" value="karyawan" class="sr-only peer" {{ old('role', $pengguna->role) == 'karyawan' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-user-tie text-3xl text-blue-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Karyawan</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Kelola produk & stok</span>
                    </label>

                    <!-- Kasir -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 transition duration-200">
                        <input type="radio" name="role" value="kasir" class="sr-only peer" {{ old('role', $pengguna->role) == 'kasir' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-green-500 peer-checked:bg-green-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-cash-register text-3xl text-green-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Kasir</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Kasir & transaksi</span>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Info Terdaftar -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <span class="font-medium">Informasi Akun</span>
                </p>
                <div class="text-sm text-gray-700">
                    <p><strong>Terdaftar sejak:</strong> {{ $pengguna->create_at->format('d F Y, H:i') }} WIB</p>
                    @if($pengguna->umur)
                        <p><strong>Umur:</strong> {{ $pengguna->umur }} tahun</p>
                    @endif
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route(role_route('pengguna.index')) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Update Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
