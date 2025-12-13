<div x-data="{ open: false }" class="flex flex-col">
    <!-- Tombol mobile -->
    <div class="md:hidden flex items-center justify-between bg-red-600 text-white p-4 shadow-lg fixed top-0 left-0 right-0 z-30">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-cookie-bite text-red-600 text-lg"></i>
            </div>
            <h2 class="text-lg font-bold">Victor Snack</h2>
        </div>
        <button @click="open = !open" class="focus:outline-none hover:bg-red-700 p-2 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside :class="{'translate-x-0': open, '-translate-x-full': !open}"
        class="md:translate-x-0 fixed top-0 left-0 w-64 h-screen bg-black text-white shadow-2xl z-20 overflow-y-auto transition-transform duration-300">
        
        <!-- Logo & Brand -->
        <div class="p-6 bg-red-600 border-b-4 border-white">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Logo" class="w-14 h-14 object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-xl text-white">Victor Snack</h1>
                    <p class="text-xs text-white">Snack Management</p>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="px-4 py-5 border-b border-gray-800 bg-gray-900">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-circle text-white"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-white">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400 capitalize">
                        @if(Auth::user()->role === 'pemilik')
                            <span class="bg-yellow-600 px-2 py-0.5 rounded text-white font-semibold flex items-center w-fit">
                                <i class="fas fa-crown mr-1 text-xs"></i>PEMILIK
                            </span>
                        @elseif(Auth::user()->role === 'karyawan')
                            <span class="bg-blue-600 px-2 py-0.5 rounded text-white font-semibold flex items-center w-fit">
                                <i class="fas fa-user-tie mr-1 text-xs"></i>KARYAWAN
                            </span>
                        @elseif(Auth::user()->role === 'kasir')
                            <span class="bg-green-600 px-2 py-0.5 rounded text-white font-semibold flex items-center w-fit">
                                <i class="fas fa-cash-register mr-1 text-xs"></i>KASIR
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <nav class="py-4">
            <ul class="space-y-1 px-3">
                {{-- MENU PEMILIK (ADMIN) --}}
                @if(Auth::user()->role === 'pemilik')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.produk.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.produk.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-box w-5"></i>
                            <span class="ml-3">Data Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.varian.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.varian.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-layer-group w-5"></i>
                            <span class="ml-3">Varian Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.stok.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.stok.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-warehouse w-5"></i>
                            <span class="ml-3">Stok Barang</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ route('admin.transaksi.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.transaksi.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span class="ml-3">Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pengguna.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pengguna.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-users w-5"></i>
                            <span class="ml-3">Kelola Pengguna</span>
                        </a>
                    </li>
                    
                    <!-- Divider -->
                    <li class="pt-3 pb-2">
                        <div class="border-t border-gray-700"></div>
                        <p class="text-xs text-gray-500 mt-3 px-4 font-semibold uppercase">Laporan</p>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.laporan.penjualan') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.laporan.penjualan') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-chart-line w-5"></i>
                            <span class="ml-3">Laporan Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.keuangan') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.laporan.keuangan') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-money-bill-wave w-5"></i>
                            <span class="ml-3">Laporan Keuangan</span>
                        </a>
                    </li>
                    

                {{-- MENU KARYAWAN --}}
                @elseif(Auth::user()->role === 'karyawan')
                    <li>
                        <a href="{{ route('karyawan.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('karyawan.dashboard') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('karyawan.produk.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('karyawan.produk.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-box w-5"></i>
                            <span class="ml-3">Data Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('karyawan.varian.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('karyawan.varian.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-layer-group w-5"></i>
                            <span class="ml-3">Varian Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('karyawan.stok.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('karyawan.stok.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-warehouse w-5"></i>
                            <span class="ml-3">Stok Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('karyawan.transaksi.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('karyawan.transaksi.*') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="ml-3">Lihat Transaksi</span>
                        </a>
                    </li>

                {{-- MENU KASIR --}}
                @elseif(Auth::user()->role === 'kasir')
                    <li>
                        <a href="{{ route('kasir.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('kasir.dashboard') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('kasir.index') || request()->routeIs('kasir.proses') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-cash-register w-5"></i>
                            <span class="ml-3">Kasir (POS)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.transaksi.saya') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('kasir.transaksi.saya') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-receipt w-5"></i>
                            <span class="ml-3">Transaksi Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.riwayat.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('kasir.riwayat.index') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-history w-5"></i>
                            <span class="ml-3">Riwayat Pemesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.produk.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('kasir.produk.index') ? 'bg-red-600 text-white font-semibold shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-box-open w-5"></i>
                            <span class="ml-3">Lihat Produk</span>
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <li class="pt-3 pb-2">
                    <div class="border-t border-gray-700"></div>
                </li>

                <!-- Logout -->
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition-all duration-200">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Overlay untuk mobile -->
    <div x-show="open" 
         @click="open = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-10"
         style="display: none;">
    </div>
</div>