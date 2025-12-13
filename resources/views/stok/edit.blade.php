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
            <span class="text-gray-900 font-medium">Update Stok</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-edit text-red-600 mr-2"></i>Update Stok Barang
        </h1>
        <p class="text-gray-600 mt-1">Kelola stok: {{ $stok->produk->nama_produk }}</p>
    </div>

    <!-- Info Stok Saat Ini -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Stok Saat Ini</p>
                <h2 class="text-5xl font-bold mt-2">{{ number_format($stok->jumlah, 2, ',', '.') }}</h2>
                <p class="text-blue-100 text-sm mt-1">{{ $stok->satuan }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-weight-hanging text-5xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-400">
            <p class="text-sm text-blue-100">
                <i class="fas fa-box mr-2"></i>{{ $stok->produk->nama_produk }}
                <span class="ml-4"><i class="fas fa-tag mr-2"></i>{{ $stok->produk->kategori }}</span>
            </p>
        </div>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-pen mr-2"></i>Form Update Stok
            </h2>
        </div>

        <form action="{{ route(role_route('stok.update'), $stok->id_stok) }}" method="POST" class="p-6 space-y-6" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <!-- Tipe Operasi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-cogs text-red-600 mr-1"></i>Pilih Tipe Operasi
                    <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Set Stok -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition duration-200">
                        <input type="radio" name="tipe" value="set" class="sr-only peer" {{ old('tipe') == 'set' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-sync-alt text-3xl text-blue-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Set Stok</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Ubah total stok ke jumlah tertentu</span>
                    </label>

                    <!-- Tambah Stok -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 transition duration-200">
                        <input type="radio" name="tipe" value="tambah" class="sr-only peer" {{ old('tipe') == 'tambah' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-green-500 peer-checked:bg-green-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-plus-circle text-3xl text-green-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Tambah Stok</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Menambahkan ke stok yang ada</span>
                    </label>

                    <!-- Kurangi Stok -->
                    <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition duration-200">
                        <input type="radio" name="tipe" value="kurang" class="sr-only peer" {{ old('tipe') == 'kurang' ? 'checked' : '' }} required>
                        <div class="peer-checked:border-red-500 peer-checked:bg-red-50 absolute inset-0 rounded-lg border-2 transition duration-200"></div>
                        <i class="fas fa-minus-circle text-3xl text-red-600 mb-2 relative z-10"></i>
                        <span class="text-sm font-semibold text-gray-700 relative z-10">Kurangi Stok</span>
                        <span class="text-xs text-gray-500 text-center mt-1 relative z-10">Mengurangi dari stok yang ada</span>
                    </label>
                </div>
                @error('tipe')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Jumlah -->
            <div>
                <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sort-numeric-up text-red-600 mr-1"></i>Jumlah ({{ $stok->satuan }})
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           name="jumlah" 
                           id="jumlah" 
                           value="{{ old('jumlah') }}"
                           min="0"
                           step="0.01"
                           class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('jumlah') border-red-500 @enderror" 
                           placeholder="Masukkan jumlah"
                           required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <span class="text-gray-500 text-sm font-medium">{{ $stok->satuan }}</span>
                    </div>
                </div>
                @error('jumlah')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Masukkan jumlah dalam satuan {{ $stok->satuan }}. Anda dapat menggunakan desimal (contoh: 2.5)
                </p>
            </div>

            <!-- Preview Hasil -->
            <div id="previewBox" class="hidden bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-300 rounded-lg p-5">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>Preview Hasil:
                </p>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-center flex-1">
                            <p class="text-xs text-gray-600 mb-1">Stok Saat Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($stok->jumlah, 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stok->satuan }}</p>
                        </div>
                        <div class="text-3xl text-gray-400 mx-4">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-xs text-gray-600 mb-1">Stok Setelah Update</p>
                            <p id="previewResult" class="text-2xl font-bold text-green-600">-</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stok->satuan }}</p>
                        </div>
                    </div>
                    <div id="operasiInfo" class="mt-3 pt-3 border-t border-gray-200 text-center hidden">
                        <p class="text-sm text-gray-600"><span id="operasiText"></span></p>
                    </div>
                </div>
            </div>

            <!-- Info Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Info Set -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-sync-alt mr-1"></i>Set Stok
                    </p>
                    <p class="text-xs text-blue-600 mt-1">
                        Mengubah total stok menjadi jumlah yang diinput (menimpa stok lama)
                    </p>
                    <p class="text-xs text-blue-500 mt-2 font-mono">
                        Stok = Input
                    </p>
                </div>

                <!-- Info Tambah -->
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700 font-medium">
                        <i class="fas fa-plus-circle mr-1"></i>Tambah Stok
                    </p>
                    <p class="text-xs text-green-600 mt-1">
                        Menambahkan jumlah input ke stok yang sudah ada
                    </p>
                    <p class="text-xs text-green-500 mt-2 font-mono">
                        Stok = {{ number_format($stok->jumlah, 2, ',', '.') }} + Input
                    </p>
                </div>

                <!-- Info Kurangi -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-minus-circle mr-1"></i>Kurangi Stok
                    </p>
                    <p class="text-xs text-red-600 mt-1">
                        Mengurangi jumlah input dari stok yang ada
                    </p>
                    <p class="text-xs text-red-500 mt-2 font-mono">
                        Stok = {{ number_format($stok->jumlah, 2, ',', '.') }} - Input
                    </p>
                </div>
            </div>

            <!-- Warning -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Perhatian!</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Pastikan operasi yang Anda pilih sudah benar. Perubahan stok akan langsung tersimpan dan mempengaruhi sistem.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route(role_route('stok.index')) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        id="submitBtn"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Update Stok</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Preview -->
<script>
const stokSekarang = {{ $stok->jumlah }};
const satuan = '{{ $stok->satuan }}';
const tipeInputs = document.querySelectorAll('input[name="tipe"]');
const jumlahInput = document.getElementById('jumlah');
const previewBox = document.getElementById('previewBox');
const previewResult = document.getElementById('previewResult');
const operasiInfo = document.getElementById('operasiInfo');
const operasiText = document.getElementById('operasiText');

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(num);
}

function updatePreview() {
    const tipe = document.querySelector('input[name="tipe"]:checked')?.value;
    const jumlah = parseFloat(jumlahInput.value) || 0;
    
    if (tipe && jumlah > 0) {
        previewBox.classList.remove('hidden');
        operasiInfo.classList.remove('hidden');
        
        let hasil = 0;
        let color = 'text-green-600';
        let operasi = '';
        
        if (tipe === 'set') {
            hasil = jumlah;
            operasi = `Stok akan diubah menjadi ${formatNumber(jumlah)} ${satuan}`;
            color = 'text-blue-600';
        } else if (tipe === 'tambah') {
            hasil = stokSekarang + jumlah;
            operasi = `${formatNumber(stokSekarang)} + ${formatNumber(jumlah)} = ${formatNumber(hasil)} ${satuan}`;
            color = 'text-green-600';
        } else if (tipe === 'kurang') {
            hasil = stokSekarang - jumlah;
            operasi = `${formatNumber(stokSekarang)} - ${formatNumber(jumlah)} = ${formatNumber(hasil)} ${satuan}`;
            
            if (hasil < 0) {
                color = 'text-red-600';
                previewResult.innerHTML = formatNumber(hasil) + ' <span class="text-xs block mt-1">(⚠️ Stok tidak cukup!)</span>';
                operasiText.innerHTML = `<span class="text-red-600 font-semibold">⚠️ Stok tidak mencukupi untuk operasi ini!</span>`;
                return;
            }
        }
        
        previewResult.className = 'text-2xl font-bold ' + color;
        previewResult.textContent = formatNumber(hasil);
        operasiText.textContent = operasi;
    } else {
        previewBox.classList.add('hidden');
        operasiInfo.classList.add('hidden');
    }
}

function validateForm() {
    const tipe = document.querySelector('input[name="tipe"]:checked')?.value;
    const jumlah = parseFloat(jumlahInput.value) || 0;
    
    if (!tipe) {
        alert('Pilih tipe operasi terlebih dahulu!');
        return false;
    }
    
    if (jumlah <= 0) {
        alert('Jumlah harus lebih dari 0!');
        return false;
    }
    
    if (tipe === 'kurang') {
        const hasil = stokSekarang - jumlah;
        if (hasil < 0) {
            alert(`Stok tidak mencukupi!\nStok tersedia: ${formatNumber(stokSekarang)} ${satuan}\nYang akan dikurangi: ${formatNumber(jumlah)} ${satuan}`);
            return false;
        }
    }
    
    // Konfirmasi sebelum submit
    const confirmMsg = `Apakah Anda yakin ingin melakukan operasi ini?\n\n` +
                      `Tipe: ${tipe.toUpperCase()}\n` +
                      `Jumlah: ${formatNumber(jumlah)} ${satuan}\n` +
                      `Stok sekarang: ${formatNumber(stokSekarang)} ${satuan}\n` +
                      `Stok setelah update: ${formatNumber(tipe === 'set' ? jumlah : (tipe === 'tambah' ? stokSekarang + jumlah : stokSekarang - jumlah))} ${satuan}`;
    
    return confirm(confirmMsg);
}

tipeInputs.forEach(input => {
    input.addEventListener('change', updatePreview);
});

jumlahInput.addEventListener('input', updatePreview);

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endsection