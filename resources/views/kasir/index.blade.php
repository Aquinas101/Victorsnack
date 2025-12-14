@extends('layouts.app')

@section('content')
<!-- Midtrans Snap Script -->
<script type="text/javascript"
        src="https://app{{ config('midtrans.is_production') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<div class="container mx-auto px-4" x-data="kasirApp()">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-cash-register text-red-600 mr-2"></i>Kasir POS
        </h1>
        <p class="text-gray-600 mt-1">Point of Sale - Sistem Kasir</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Info</p>
            <p>{{ session('info') }}</p>
        </div>
    @endif

    <!-- Main Layout: 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: Daftar Produk (2/3) -->
        <div class="lg:col-span-2">
            <!-- Filter & Search -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                <form action="{{ route('kasir.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari produk..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    
                    <!-- Filter Kategori -->
                    <div class="flex space-x-2">
                        <select name="kategori" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Grid Produk -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-box mr-2"></i>Pilih Produk
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto">
                    @forelse($produks as $produk)
                        @php
                            $stok = $produk->stok ? $produk->stok->jumlah : 0;
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition duration-200 {{ $stok == 0 ? 'opacity-50' : '' }}">
                            <!-- Gambar/Icon Produk -->
                            <div class="w-full h-24 bg-red-100 rounded-lg flex items-center justify-center mb-3 overflow-hidden">
                                @if(isset($produk->gambar) && $produk->gambar)
                                    <img src="{{ asset($produk->gambar) }}" 
                                        alt="{{ $produk->nama_produk }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-cookie-bite text-4xl text-red-600"></i>
                                @endif
                            </div>
                                                        
                            <!-- Info Produk -->
                            <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2">{{ $produk->nama_produk }}</h3>
                            <p class="text-xs text-gray-500 mb-2">{{ $produk->kategori }}</p>
                            
                            <!-- Stok -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs {{ $stok == 0 ? 'text-red-600' : ($stok < 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                    <i class="fas fa-box mr-1"></i>Stok: {{ $stok }}
                                </span>
                            </div>
                            
                            <!-- Varian -->
                            @if($produk->varians->count() > 0)
                                <select class="w-full text-xs border border-gray-300 rounded px-2 py-1 mb-2" 
                                        x-ref="varian_{{ $produk->id_produk }}"
                                        @change="selectVarian($event.target.value, {{ $produk->id_produk }})">
                                    <option value="">Pilih Varian</option>
                                    @foreach($produk->varians as $varian)
                                        <option value="{{ $varian->id_varian }}" data-harga="{{ $varian->harga }}" data-berat="{{ $varian->berat }}">
                                            {{ $varian->berat }}g - Rp {{ number_format($varian->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="button" 
                                        @click="tambahKeKeranjang({{ $produk->id_produk }}, '{{ $produk->nama_produk }}', {{ $stok }})"
                                        :disabled="!selectedVarian[{{ $produk->id_produk }}] || {{ $stok }} == 0"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white text-xs py-2 rounded transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                    <i class="fas fa-cart-plus mr-1"></i>Tambah
                                </button>
                            @else
                                <p class="text-xs text-red-500 text-center">Belum ada varian</p>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2"></i>
                            <p>Tidak ada produk</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($produks->hasPages())
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        {{ $produks->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Keranjang (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-4 sticky top-4">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                    <span class="text-sm font-normal text-gray-500" x-text="'(' + keranjang.length + ' item)'"></span>
                </h2>

                <!-- List Keranjang -->
                <div class="max-h-[300px] overflow-y-auto mb-4">
                    <template x-if="keranjang.length === 0">
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                            <p class="text-sm">Keranjang kosong</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in keranjang" :key="index">
                        <div class="border-b border-gray-200 py-3">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-gray-800" x-text="item.nama"></p>
                                    <p class="text-xs text-gray-500" x-text="item.berat + 'g'"></p>
                                </div>
                                <button @click="hapusDariKeranjang(index)" 
                                        class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button @click="kurangiQty(index)" 
                                            class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-8 text-center font-semibold" x-text="item.qty"></span>
                                    <button @click="tambahQty(index)" 
                                            class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <p class="font-bold text-sm text-green-600" x-text="formatRupiah(item.subtotal)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Summary -->
                <div class="border-t-2 border-gray-300 pt-4 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Total Item:</span>
                        <span class="font-semibold" x-text="totalItem"></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-bold text-gray-800">TOTAL:</span>
                        <span class="text-2xl font-bold text-green-600" x-text="formatRupiah(totalHarga)"></span>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                    <select x-model="metodePembayaran" 
                            @change="onMetodeChange()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">Pilih Metode</option>
                        <option value="tunai">💵 Tunai</option>
                        <option value="digital">💳 Digital Payment (Midtrans)</option>
                    </select>
                </div>

                <!-- Input Uang Tunai (jika tunai) -->
                <template x-if="metodePembayaran === 'tunai'">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Uang Diterima</label>
                        <input type="number" 
                               x-model.number="uangDiterima" 
                               @input="hitungKembalian()"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" 
                               placeholder="0">
                        <template x-if="kembalian >= 0 && uangDiterima > 0">
                            <p class="mt-2 text-sm">
                                <span class="text-gray-600">Kembalian:</span>
                                <span class="font-bold text-green-600" x-text="formatRupiah(kembalian)"></span>
                            </p>
                        </template>
                        <template x-if="kembalian < 0 && uangDiterima > 0">
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>Uang tidak cukup!
                            </p>
                        </template>
                    </div>
                </template>

                <!-- Info Digital Payment -->
                <template x-if="metodePembayaran === 'digital'">
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                        <p class="text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda akan diarahkan ke halaman pembayaran Midtrans
                        </p>
                    </div>
                </template>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <button @click="prosesTransaksi()" 
                            :disabled="keranjang.length === 0 || !metodePembayaran || (metodePembayaran === 'tunai' && kembalian < 0)"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i>PROSES PEMBAYARAN
                    </button>
                    <button @click="resetKeranjang()" 
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg transition duration-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Success -->
    <div x-show="showSuccessModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showSuccessModal = false">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4" @click.stop>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-3xl text-green-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Transaksi Berhasil!</h3>
                <p class="text-gray-600 mb-4">ID Transaksi: <span class="font-bold" x-text="'#' + transaksiId"></span></p>
                
                <template x-if="metodePembayaran === 'tunai' && kembalian > 0">
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-600">Kembalian:</p>
                        <p class="text-2xl font-bold text-green-600" x-text="formatRupiah(kembalian)"></p>
                    </div>
                </template>

                <div class="flex space-x-2">
                    <template x-if="cetakUrl">
                        <button @click="cetakStruk()" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
                            <i class="fas fa-print mr-2"></i>Cetak Struk
                        </button>
                    </template>
                    <button @click="transaksiBaru()" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="isLoading" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-4xl text-red-600 mb-4"></i>
                <p class="text-gray-700 font-semibold">Memproses transaksi...</p>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script>
function kasirApp() {
    return {
        keranjang: [],
        selectedVarian: {},
        metodePembayaran: '',
        uangDiterima: 0,
        kembalian: 0,
        showSuccessModal: false,
        transaksiId: null,
        cetakUrl: '',
        isLoading: false,

        get totalItem() {
            return this.keranjang.reduce((sum, item) => sum + item.qty, 0);
        },

        get totalHarga() {
            return this.keranjang.reduce((sum, item) => sum + item.subtotal, 0);
        },

        onMetodeChange() {
            if (this.metodePembayaran !== 'tunai') {
                this.uangDiterima = 0;
                this.kembalian = 0;
            }
        },

        selectVarian(idVarian, idProduk) {
            if (!idVarian) {
                delete this.selectedVarian[idProduk];
                return;
            }

            const selectElement = this.$refs['varian_' + idProduk];
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            
            this.selectedVarian[idProduk] = {
                id_varian: idVarian,
                harga: parseFloat(selectedOption.dataset.harga),
                berat: parseInt(selectedOption.dataset.berat)
            };
        },

        tambahKeKeranjang(idProduk, namaProduk, stokTersedia) {
            const varian = this.selectedVarian[idProduk];
            
            if (!varian) {
                alert('Pilih varian terlebih dahulu!');
                return;
            }

            if (stokTersedia <= 0) {
                alert('Stok habis!');
                return;
            }

            const existingIndex = this.keranjang.findIndex(item => item.id_varian === varian.id_varian);
            
            if (existingIndex !== -1) {
                const newQty = this.keranjang[existingIndex].qty + 1;
                if (newQty > stokTersedia) {
                    alert('Stok tidak mencukupi! Stok tersedia: ' + stokTersedia);
                    return;
                }
                this.keranjang[existingIndex].qty = newQty;
                this.keranjang[existingIndex].subtotal = this.keranjang[existingIndex].qty * this.keranjang[existingIndex].harga;
            } else {
                this.keranjang.push({
                    id_varian: varian.id_varian,
                    nama: namaProduk,
                    berat: varian.berat,
                    harga: varian.harga,
                    qty: 1,
                    subtotal: varian.harga,
                    stok_max: stokTersedia
                });
            }

            const selectElement = this.$refs['varian_' + idProduk];
            if (selectElement) {
                selectElement.selectedIndex = 0;
            }
            delete this.selectedVarian[idProduk];
        },

        tambahQty(index) {
            const item = this.keranjang[index];
            if (item.qty >= item.stok_max) {
                alert('Stok tidak mencukupi! Stok tersedia: ' + item.stok_max);
                return;
            }
            item.qty++;
            item.subtotal = item.qty * item.harga;
        },

        kurangiQty(index) {
            const item = this.keranjang[index];
            if (item.qty > 1) {
                item.qty--;
                item.subtotal = item.qty * item.harga;
            } else {
                this.hapusDariKeranjang(index);
            }
        },

        hapusDariKeranjang(index) {
            if (confirm('Hapus item ini dari keranjang?')) {
                this.keranjang.splice(index, 1);
            }
        },

        resetKeranjang() {
            if (this.keranjang.length === 0) return;
            
            if (confirm('Reset keranjang? Semua item akan dihapus!')) {
                this.keranjang = [];
                this.metodePembayaran = '';
                this.uangDiterima = 0;
                this.kembalian = 0;
            }
        },

        hitungKembalian() {
            this.kembalian = this.uangDiterima - this.totalHarga;
        },

        // ✅ FUNGSI HELPER UNTUK FORCE HTTPS
        getSecureUrl(path) {
            const currentUrl = window.location.href;
            const isHttps = currentUrl.startsWith('https://');
            
            if (isHttps && path.startsWith('http://')) {
                return path.replace('http://', 'https://');
            }
            
            return path;
        },

        async prosesTransaksi() {
            if (this.keranjang.length === 0) {
                alert('Keranjang kosong!');
                return;
            }

            if (!this.metodePembayaran) {
                alert('Pilih metode pembayaran!');
                return;
            }

            // ========== PEMBAYARAN TUNAI ==========
            if (this.metodePembayaran === 'tunai') {
                if (this.kembalian < 0) {
                    alert('Uang tidak cukup!');
                    return;
                }

                if (!confirm('Proses transaksi tunai ini?')) {
                    return;
                }

                this.isLoading = true;

                try {
                    // ✅ FIX: Paksa HTTPS untuk fetch URL
                    const url = this.getSecureUrl('{{ route("kasir.proses") }}');
                    
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            items: this.keranjang.map(item => ({
                                id_varian: item.id_varian,
                                jumlah: item.qty
                            })),
                            total_harga: this.totalHarga,
                            metode_pembayaran: 'tunai',
                            uang_diterima: this.uangDiterima
                        })
                    });

                    if (!response.ok) {
                        const text = await response.text();
                        console.error('Response Status:', response.status);
                        console.error('Response Body:', text);
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        this.transaksiId = data.data.id_transaksi;
                        this.cetakUrl = data.data.cetak_url;
                        this.kembalian = data.data.kembalian;
                        this.showSuccessModal = true;
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                } finally {
                    this.isLoading = false;
                }
            } 
            // ========== PEMBAYARAN DIGITAL (MIDTRANS) ==========
            else if (this.metodePembayaran === 'digital') {
                this.isLoading = true;

                try {
                    console.log('=== STEP 1: Creating Payment Token ===');
                    
                    // ✅ FIX: Paksa HTTPS untuk fetch URL
                    const tokenUrl = this.getSecureUrl('{{ route("kasir.create-token") }}');
                    
                    // Step 1: Create Midtrans Token
                    const tokenResponse = await fetch(tokenUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            items: this.keranjang.map(item => ({
                                id_varian: item.id_varian,
                                jumlah: item.qty
                            })),
                            total_harga: this.totalHarga
                        })
                    });

                    console.log('Token Response Status:', tokenResponse.status);
                    console.log('Token Response Headers:', [...tokenResponse.headers.entries()]);

                    if (!tokenResponse.ok) {
                        const text = await tokenResponse.text();
                        console.error('Token Response Text:', text);
                        
                        this.isLoading = false;
                        
                        try {
                            const errorData = JSON.parse(text);
                            alert('Gagal membuat payment token: ' + (errorData.message || 'Unknown error'));
                        } catch (e) {
                            alert('Server error! Cek console untuk detail. Status: ' + tokenResponse.status);
                            console.error('Full HTML Response:', text);
                        }
                        return;
                    }

                    const contentType = tokenResponse.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await tokenResponse.text();
                        console.error('Non-JSON Response:', text);
                        this.isLoading = false;
                        alert('Server mengembalikan HTML error. Kemungkinan:\n1. Midtrans config belum diatur\n2. Route error\n3. Server error\n\nCek console (F12) untuk detail.');
                        return;
                    }

                    const tokenData = await tokenResponse.json();
                    console.log('Token Data:', tokenData);
                    
                    if (!tokenData.success) {
                        this.isLoading = false;
                        alert('Gagal membuat payment: ' + tokenData.message);
                        return;
                    }

                    const snapToken = tokenData.snap_token;
                    const orderId = tokenData.order_id;

                    console.log('✓ Snap Token received:', snapToken.substring(0, 20) + '...');
                    console.log('✓ Order ID:', orderId);

                    // Step 2: Create Transaction in DB
                    console.log('=== STEP 2: Creating Transaction in DB ===');
                    
                    // ✅ FIX: Paksa HTTPS untuk fetch URL
                    const createUrl = this.getSecureUrl('{{ route("kasir.process-payment") }}');
                    
                    const createResponse = await fetch(createUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            items: this.keranjang.map(item => ({
                                id_varian: item.id_varian,
                                jumlah: item.qty
                            }))
                        })
                    });

                    if (!createResponse.ok) {
                        const text = await createResponse.text();
                        console.error('Create Transaction Response:', text);
                        this.isLoading = false;
                        alert('Gagal membuat transaksi di database!');
                        return;
                    }

                    const createData = await createResponse.json();
                    console.log('✓ Transaction Created:', createData);
                    
                    if (!createData.success) {
                        this.isLoading = false;
                        alert('Gagal membuat transaksi: ' + createData.message);
                        return;
                    }

                    this.isLoading = false;

                    // Step 3: Open Midtrans Snap
                    console.log('=== STEP 3: Opening Midtrans Snap ===');
                    
                    if (typeof window.snap === 'undefined') {
                        alert('Midtrans Snap belum load! Refresh halaman dan coba lagi.');
                        return;
                    }

                    window.snap.pay(snapToken, {
                        onSuccess: (result) => {
                            console.log('✓ Payment Success:', result);
                            
                            this.transaksiId = createData.transaksi_id;
                            this.cetakUrl = '{{ url("kasir/transaksi") }}/' + createData.transaksi_id + '/cetak';
                            this.showSuccessModal = true;
                        },
                        onPending: (result) => {
                            console.log('⏳ Payment Pending:', result);
                            
                            alert('Pembayaran pending. Transaksi ID: ' + createData.transaksi_id + '\n\nSilakan selesaikan pembayaran Anda.');
                            this.transaksiBaru();
                        },
                        onError: (result) => {
                            console.error('❌ Payment Error:', result);
                            
                            alert('Pembayaran gagal!\n\nDetail: ' + (result.status_message || 'Unknown error'));
                        },
                        onClose: () => {
                            console.log('🔒 Popup closed by user');
                            
                            alert('Popup pembayaran ditutup. Transaksi dalam status pending.');
                        }
                    });

                } catch (error) {
                    this.isLoading = false;
                    console.error('❌ Critical Error:', error);
                    console.error('Error Stack:', error.stack);
                    
                    alert('Terjadi kesalahan:\n\n' + error.message + '\n\nCek console (F12) untuk detail lengkap.');
                }
            }
        },

        cetakStruk() {
            if (this.cetakUrl) {
                window.open(this.cetakUrl, '_blank');
            }
        },

        transaksiBaru() {
            this.showSuccessModal = false;
            this.keranjang = [];
            this.metodePembayaran = '';
            this.uangDiterima = 0;
            this.kembalian = 0;
            this.transaksiId = null;
            this.cetakUrl = '';
        },

        formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}
</script>

<style>
[x-cloak] { 
    display: none !important; 
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.max-h-\[600px\]::-webkit-scrollbar,
.max-h-\[300px\]::-webkit-scrollbar {
    width: 6px;
}

.max-h-\[600px\]::-webkit-scrollbar-track,
.max-h-\[300px\]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.max-h-\[600px\]::-webkit-scrollbar-thumb,
.max-h-\[300px\]::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.max-h-\[600px\]::-webkit-scrollbar-thumb:hover,
.max-h-\[300px\]::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection