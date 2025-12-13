@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route(role_route('produk.index')) }}" class="hover:text-red-600 transition">
                <i class="fas fa-box"></i> Data Produk
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit Produk</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-edit text-red-600 mr-2"></i>Edit Produk
        </h1>
        <p class="text-gray-600 mt-1">Perbarui informasi produk</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-pen mr-2"></i>Form Edit Produk
            </h2>
        </div>

        <form action="{{ route(role_route('produk.update'), $produk->id_produk) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Produk -->
            <div>
                <label for="nama_produk" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-cookie-bite text-red-600 mr-1"></i>Nama Produk
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_produk" 
                       id="nama_produk" 
                       value="{{ old('nama_produk', $produk->nama_produk) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('nama_produk') border-red-500 @enderror" 
                       placeholder="Contoh: Chitato Sapi Panggang"
                       required>
                @error('nama_produk')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag text-red-600 mr-1"></i>Kategori
                    <span class="text-red-500">*</span>
                </label>
                <select name="kategori" 
                        id="kategori" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('kategori') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Snack" {{ old('kategori', $produk->kategori) == 'Snack' ? 'selected' : '' }}>Snack</option>
                    <option value="Makanan Ringan" {{ old('kategori', $produk->kategori) == 'Makanan Ringan' ? 'selected' : '' }}>Makanan Ringan</option>
                    <option value="Keripik" {{ old('kategori', $produk->kategori) == 'Keripik' ? 'selected' : '' }}>Keripik</option>
                    <option value="Biskuit" {{ old('kategori', $produk->kategori) == 'Biskuit' ? 'selected' : '' }}>Biskuit</option>
                    <option value="Permen" {{ old('kategori', $produk->kategori) == 'Permen' ? 'selected' : '' }}>Permen</option>
                    <option value="Cokelat" {{ old('kategori', $produk->kategori) == 'Cokelat' ? 'selected' : '' }}>Cokelat</option>
                    <option value="Minuman" {{ old('kategori', $produk->kategori) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    <option value="Lainnya" {{ old('kategori', $produk->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Gambar Produk -->
            <div>
                <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-red-600 mr-1"></i>Gambar Produk
                    <span class="text-gray-500 text-xs font-normal">(Opsional)</span>
                </label>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div id="imagePreview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50">
                            @if($produk->gambar)
                                <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                        <p class="text-xs text-gray-500">Preview</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($produk->gambar)
                            <p class="text-xs text-gray-500 mt-2 text-center">Gambar saat ini</p>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" 
                               name="gambar" 
                               id="gambar" 
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               onchange="previewImage(event)"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('gambar') border-red-500 @enderror">
                        @error('gambar')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Format: JPG, JPEG, PNG, GIF. Maksimal 2MB
                        </p>
                        @if($produk->gambar)
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-lightbulb mr-1"></i>Kosongkan jika tidak ingin mengubah gambar
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Tanggal -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">
                            <i class="far fa-calendar-plus text-blue-500 mr-2"></i>
                            <span class="font-medium">Dibuat:</span>
                        </p>
                        <p class="text-gray-900 ml-6">{{ $produk->create_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="far fa-calendar-check text-green-500 mr-2"></i>
                            <span class="font-medium">Terakhir Diperbarui:</span>
                        </p>
                        <p class="text-gray-900 ml-6">{{ $produk->update_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
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
                            Mengubah informasi produk tidak akan mempengaruhi varian produk yang sudah ada. Jika perlu mengubah varian, silakan perbarui di menu <strong>Varian Produk</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route(role_route('produk.index')) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Update Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection