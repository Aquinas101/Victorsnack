@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
            <a href="{{ route(role_route('stok.index')) }}" class="hover:text-red-600 transition">
                <i class="fas fa-warehouse"></i> Stok Barang
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Tambah Stok</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-plus-circle text-red-600 mr-2"></i>Tambah Stok Barang
        </h1>
        <p class="text-gray-600 mt-1">Tambahkan stok untuk produk baru</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i>Form Tambah Stok
            </h2>
        </div>

        <form action="{{ route(role_route('stok.store')) }}" method="POST" class="p-6 space-y-6">
            @csrf

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
                    @forelse($produks as $produk)
                        <option value="{{ $produk->id_produk }}" {{ old('id_produk') == $produk->id_produk ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} ({{ $produk->kategori }})
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada produk tersedia (semua produk sudah memiliki stok)</option>
                    @endforelse
                </select>
                @error('id_produk')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Hanya produk yang belum memiliki stok yang ditampilkan
                </p>
            </div>

            <!-- Jumlah Stok dan Satuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Jumlah Stok -->
                <div>
                    <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-weight text-red-600 mr-1"></i>Jumlah Stok Awal
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="jumlah" 
                               id="jumlah" 
                               value="{{ old('jumlah', 0) }}"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('jumlah') border-red-500 @enderror" 
                               placeholder="Contoh: 10.5"
                               required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 text-sm" id="satuanDisplay">kg</span>
                        </div>
                    </div>
                    @error('jumlah')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-balance-scale text-red-600 mr-1"></i>Satuan
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="satuan" 
                            id="satuan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('satuan') border-red-500 @enderror"
                            onchange="updateSatuanDisplay()"
                            required>
                        <option value="kg" {{ old('satuan', 'kg') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                    </select>
                    @error('satuan')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>Masukkan jumlah stok awal dan pilih satuan yang sesuai. Anda dapat menggunakan desimal (contoh: 2.5 kg)
            </p>

            <!-- Preview Box -->
            <div id="previewBox" class="hidden bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-lg p-5">
                <div class="flex items-center mb-3">
                    <i class="fas fa-eye text-blue-600 text-xl mr-2"></i>
                    <p class="text-sm font-semibold text-gray-700">Preview Stok</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Produk yang dipilih</p>
                            <p id="previewProduk" class="text-lg font-bold text-gray-800">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-600 mb-1">Stok awal</p>
                            <p id="previewStok" class="text-2xl font-bold text-blue-600">0 kg</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 font-medium">Informasi</p>
                        <ul class="text-sm text-blue-600 mt-1 space-y-1">
                            <li>• Stok ini adalah stok produk secara keseluruhan, bukan per varian</li>
                            <li>• Setelah menambahkan stok, Anda dapat melakukan update stok di menu edit</li>
                            <li>• Gunakan kilogram (kg) untuk produk dengan berat besar</li>
                            <li>• Gunakan gram (g) untuk produk dengan berat kecil atau sampel</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if($produks->isEmpty())
            <!-- Warning jika tidak ada produk -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Tidak Ada Produk Tersedia</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Semua produk sudah memiliki stok. Silakan tambahkan produk baru terlebih dahulu di menu <strong>Data Produk</strong> jika ingin menambahkan stok baru.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route(role_route('stok.index')) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg transition duration-200 inline-flex items-center"
                        @if($produks->isEmpty()) disabled @endif>
                    <i class="fas fa-save mr-2"></i>
                    <span>Simpan Stok</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
function updateSatuanDisplay() {
    const satuan = document.getElementById('satuan').value;
    document.getElementById('satuanDisplay').textContent = satuan;
    updatePreview();
}

function updatePreview() {
    const produkSelect = document.getElementById('id_produk');
    const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
    const satuan = document.getElementById('satuan').value;
    const previewBox = document.getElementById('previewBox');
    
    if (produkSelect.value && jumlah > 0) {
        previewBox.classList.remove('hidden');
        
        const produkText = produkSelect.options[produkSelect.selectedIndex].text;
        document.getElementById('previewProduk').textContent = produkText;
        
        const formattedJumlah = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(jumlah);
        
        document.getElementById('previewStok').textContent = formattedJumlah + ' ' + satuan;
    } else {
        previewBox.classList.add('hidden');
    }
}

// Event listeners
document.getElementById('id_produk').addEventListener('change', updatePreview);
document.getElementById('jumlah').addEventListener('input', updatePreview);
document.getElementById('satuan').addEventListener('change', updatePreview);

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateSatuanDisplay();
});
</script>
@endsection