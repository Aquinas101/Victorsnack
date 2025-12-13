@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route(role_route('varian.index')) }}" class="hover:text-red-600 transition">
                <i class="fas fa-layer-group"></i> Varian Produk
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit Varian</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-edit text-red-600 mr-2"></i>Edit Varian Produk
        </h1>
        <p class="text-gray-600 mt-1">Perbarui informasi varian produk</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-pen mr-2"></i>Form Edit Varian
            </h2>
        </div>

        <form action="{{ route(role_route('varian.update'), $varian->id_varian) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Pilih Produk -->
            <div>
                <label for="id_produk" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-box text-red-600 mr-1"></i>Pilih Produk
                    <span class="text-red-500">*</span>
                </label>
                <select name="id_produk" 
                        id="id_produk" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('id_produk') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id_produk }}" {{ old('id_produk', $varian->id_produk) == $produk->id_produk ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} ({{ $produk->kategori }})
                        </option>
                    @endforeach
                </select>
                @error('id_produk')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Berat -->
            <div>
                <label for="berat" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-weight text-red-600 mr-1"></i>Berat (gram)
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="berat" 
                       id="berat" 
                       value="{{ old('berat', $varian->berat) }}"
                       min="1"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('berat') border-red-500 @enderror" 
                       placeholder="Contoh: 68"
                       required>
                @error('berat')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Masukkan berat dalam satuan gram (contoh: 50, 100, 150)
                </p>
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave text-red-600 mr-1"></i>Harga (Rp)
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="number" 
                           name="harga" 
                           id="harga" 
                           value="{{ old('harga', $varian->harga) }}"
                           min="0"
                           step="0.01"
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('harga') border-red-500 @enderror" 
                           placeholder="10000"
                           required>
                </div>
                @error('harga')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Masukkan harga tanpa titik atau koma (contoh: 10000)
                </p>
            </div>

            <!-- Warning Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Perhatian!</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Mengubah varian produk akan mempengaruhi transaksi yang menggunakan varian ini. Pastikan perubahan sudah benar.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route(role_route('varian.index')) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Update Varian</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection