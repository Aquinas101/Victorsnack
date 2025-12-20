<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Victor Snack - Kelezatan dalam Setiap Gigitan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }

        .process-step {
            position: relative;
        }

        .process-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, #dc2626, transparent);
        }

        @media (max-width: 768px) {
            .process-step::after {
                display: none;
            }
        }
    </style>
</head>
<body class="scroll-smooth">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cookie-bite text-white text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">
                        Victor Snack
                    </span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-red-600 font-medium transition">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-red-600 font-medium transition">Tentang</a>
                    <a href="#produk" class="text-gray-700 hover:text-red-600 font-medium transition">Produk</a>
                    <a href="#keunggulan" class="text-gray-700 hover:text-red-600 font-medium transition">Keunggulan</a>
                    <a href="#kontak" class="text-gray-700 hover:text-red-600 font-medium transition">Kontak</a>
                    <a href="/login" class="bg-gradient-to-r from-red-600 to-red-800 text-white px-6 py-2 rounded-full hover:shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 space-y-3">
                <a href="#home" class="block text-gray-700 hover:text-red-600 font-medium transition">Beranda</a>
                <a href="#tentang" class="block text-gray-700 hover:text-red-600 font-medium transition">Tentang</a>
                <a href="#produk" class="block text-gray-700 hover:text-red-600 font-medium transition">Produk</a>
                <a href="#keunggulan" class="block text-gray-700 hover:text-red-600 font-medium transition">Keunggulan</a>
                <a href="#kontak" class="block text-gray-700 hover:text-red-600 font-medium transition">Kontak</a>
                <a href="/login" class="block bg-gradient-to-r from-red-600 to-red-800 text-white px-6 py-2 rounded-full hover:shadow-lg transition text-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient min-h-screen flex items-center pt-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white animate-fade-in-up">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Kelezatan dalam<br>
                        <span class="text-yellow-300">Setiap Gigitan</span>
                    </h1>
                    <p class="text-xl mb-8 text-red-100">
                        Nikmati koleksi snack pilihan dengan cita rasa autentik Indonesia. 
                        Dari keripik pisang renyah hingga basreng pedas, kami hadirkan kelezatan untuk setiap momen.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#produk" class="bg-white text-red-600 px-8 py-4 rounded-full font-bold hover:bg-yellow-300 hover:text-red-700 transition transform hover:scale-105 shadow-xl">
                            <i class="fas fa-shopping-bag mr-2"></i>Lihat Produk
                        </a>
                        <a href="/login" class="glassmorphism text-white px-8 py-4 rounded-full font-bold hover:bg-white/20 transition transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sistem
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300">5+</div>
                            <div class="text-red-200 text-sm mt-1">Tahun Berpengalaman</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300">15+</div>
                            <div class="text-red-200 text-sm mt-1">Varian Produk</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300">100%</div>
                            <div class="text-red-200 text-sm mt-1">Halal & Aman</div>
                        </div>
                    </div>
                </div>

                <div class="relative animate-float">
                    <div class="relative z-10">
                        <img src="https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=600&h=600&fit=crop" 
                             alt="Snack Victor" 
                             class="rounded-3xl shadow-2xl w-full">
                    </div>
                    <!-- Floating Elements -->
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-yellow-300 rounded-full opacity-20 blur-2xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-red-300 rounded-full opacity-20 blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Victor Snack Section -->
    <section id="tentang" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=600&h=400&fit=crop" 
                         alt="Victor Snack Store" 
                         class="rounded-3xl shadow-2xl">
                    <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-red-600 to-red-800 text-white p-8 rounded-2xl shadow-xl">
                        <div class="text-4xl font-bold mb-1">5+</div>
                        <div class="text-sm">Tahun Berpengalaman</div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                        Tentang <span class="bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">Victor Snack</span>
                    </h2>
                    <p class="text-gray-600 text-lg mb-4 leading-relaxed">
                        <strong class="text-gray-800">Victor Snack</strong> adalah produsen camilan khas Indonesia yang telah berdiri sejak 5 tahun lalu. Kami berkomitmen menghadirkan berbagai varian snack berkualitas dengan cita rasa autentik yang menggugah selera.
                    </p>
                    <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                        Dari keripik pisang yang renyah hingga basreng pedas yang menggigit, setiap produk kami dibuat dengan resep rahasia keluarga dan bahan-bahan pilihan untuk memastikan kualitas terbaik sampai ke tangan Anda.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Bahan Berkualitas</h4>
                                <p class="text-sm text-gray-600">Hanya bahan terbaik yang kami gunakan</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">100% Halal</h4>
                                <p class="text-sm text-gray-600">Terjamin halal dan aman dikonsumsi</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Higienis</h4>
                                <p class="text-sm text-gray-600">Proses produksi yang bersih dan modern</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Rasa Autentik</h4>
                                <p class="text-sm text-gray-600">Resep rahasia yang khas dan lezat</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#produk" class="bg-gradient-to-r from-red-600 to-red-800 text-white px-6 py-3 rounded-full font-bold hover:shadow-lg transition transform hover:scale-105">
                            <i class="fas fa-shopping-bag mr-2"></i>Lihat Produk
                        </a>
                        <a href="#kontak" class="border-2 border-red-600 text-red-600 px-6 py-3 rounded-full font-bold hover:bg-red-50 transition">
                            <i class="fas fa-phone mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Section -->
    <section id="produk" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Produk <span class="bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">Unggulan</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Dibuat dengan bahan pilihan dan resep rahasia untuk menghadirkan rasa yang tak terlupakan
                </p>
            </div>

            <div class="grid md:grid-cols-3 lg:grid-cols-5 gap-8">
                <!-- Product Card 1 -->
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('img/produk/basreng.png') }}"
                            alt="Basreng"
                            class="w-full h-full object-cover">

                        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            Best Seller
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Basreng Pedas
                        </h3>

                        <p class="text-gray-600 mb-4">
                            Basreng renyah dengan cita rasa pedas gurih
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">
                                Rp 12.000
                            </span>
                            <span class="text-gray-500 text-sm">
                                250 gr
                            </span>
                        </div>
                    </div>
                </div>


                <!-- Product Card 2 -->
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('img/produk/pisang.png') }}"
                            alt="Keripik Pisang"
                            class="w-full h-full object-cover brightness-90">
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Keripik Pisang
                        </h3>

                        <p class="text-gray-600 mb-4">
                            Keripik pisang renyah dengan rasa gurih dan manis alami
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">
                                Rp 12.000
                            </span>
                            <span class="text-gray-500 text-sm">
                                250 gr
                            </span>
                        </div>
                    </div>
                </div>


                <!-- Product Card 3 -->
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('img/produk/emping.png') }}" 
                            alt="Emping Pedas" 
                            class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            <i class="fas fa-fire mr-1"></i>Pedas
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Emping Pedas</h3>
                        <p class="text-gray-600 mb-4">Emping renyah dengan cita rasa pedas menggugah selera</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">Rp 10.000</span>
                            <span class="text-gray-500 text-sm">250 gr</span>
                        </div>
                    </div>
                </div>
                <!-- Product Card 4 -->
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('img/produk/kripca.png') }}"
                            alt="Kripik Kaca Pedas"
                            class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                            <i class="fas fa-fire mr-1"></i>Extra Pedas
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Kripik Kaca Pedas</h3>
                        <p class="text-gray-600 mb-4">Kripik kaca transparan dengan rasa pedas gurih</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">Rp 10.000</span>
                            <span class="text-gray-500 text-sm">150 gr</span>
                        </div>
                    </div>
                </div>


                <!-- Product Card 5 -->
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('img/produk/stiktela.png') }}"
                            alt="Kripik Stik Tela"
                            class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            Favorit
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Kripik Stik Tela
                        </h3>

                        <p class="text-gray-600 mb-4">
                            Kripik singkong berbentuk stik dengan rasa gurih dan renyah
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">
                                Rp 10.000
                            </span>
                            <span class="text-gray-500 text-sm">
                                200 gr
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
<section id="keunggulan" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Kenapa Pilih <span class="bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">Victor Snack?</span>
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Kami menghadirkan camilan berkualitas dengan cita rasa terbaik dan harga terjangkau
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <!-- Variasi Rasa -->
            <div class="text-center p-6 rounded-2xl hover:bg-red-50 transition">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-pepper-hot text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Banyak Varian Rasa</h3>
                <p class="text-gray-600">
                    Tersedia rasa original, pedas, gurih, hingga level ekstra pedas
                </p>
            </div>

            <!-- Harga Terjangkau -->
            <div class="text-center p-6 rounded-2xl hover:bg-red-50 transition">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">
                    Harga ramah kantong dengan kualitas yang tetap terjaga
                </p>
            </div>

            <!-- Fresh & Renyah -->
            <div class="text-center p-6 rounded-2xl hover:bg-red-50 transition">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-smile-beam text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Fresh & Renyah</h3>
                <p class="text-gray-600">
                    Diproduksi secara berkala agar selalu renyah dan nikmat
                </p>
            </div>

            <!-- Cocok Semua Kalangan -->
            <div class="text-center p-6 rounded-2xl hover:bg-red-50 transition">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Cocok Semua Kalangan</h3>
                <p class="text-gray-600">
                    Digemari anak-anak, remaja, hingga orang dewasa
                </p>
            </div>
        </div>
    </div>
</section>

    <!-- Varian Rasa Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Beragam <span class="bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">Varian Rasa</span>
                </h2>
                <p class="text-gray-600 text-lg">Temukan rasa favorit Anda dari berbagai pilihan yang tersedia</p>
            </div>

            <div class="grid md:grid-cols-3 lg:grid-cols-6 gap-6">
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🍌</div>
                    <h4 class="font-bold text-gray-800 mb-1">Original</h4>
                    <p class="text-sm text-gray-600">Manis Alami</p>
                </div>

                <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🍫</div>
                    <h4 class="font-bold text-gray-800 mb-1">Coklat</h4>
                    <p class="text-sm text-gray-600">Premium</p>
                </div>

                <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🌶️</div>
                    <h4 class="font-bold text-gray-800 mb-1">Balado</h4>
                    <p class="text-sm text-gray-600">Pedas Sedang</p>
                </div>

                <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🧀</div>
                    <h4 class="font-bold text-gray-800 mb-1">Keju</h4>
                    <p class="text-sm text-gray-600">Gurih Creamy</p>
                </div>

                <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🧂</div>
                    <h4 class="font-bold text-gray-800 mb-1">Asin</h4>
                    <p class="text-sm text-gray-600">Gurih Klasik</p>
                </div>

                <div class="bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:scale-105">
                    <div class="text-4xl mb-3">🍯</div>
                    <h4 class="font-bold text-gray-800 mb-1">Madu</h4>
                    <p class="text-sm text-gray-600">Manis Sehat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Metode Pembayaran Section -->
    <section class="py-20 hero-gradient">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Metode Pembayaran
                </h2>
                <p class="text-xl text-red-100">
                    Kami menyediakan berbagai metode pembayaran untuk kemudahan Anda
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <!-- Tunai -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center hover:bg-white/20 transition transform hover:scale-105">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-money-bill-wave text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Tunai</h3>
                    <p class="text-red-100 text-sm">Bayar langsung di toko atau saat COD</p>
                </div>

                <!-- Transfer Bank -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center hover:bg-white/20 transition transform hover:scale-105">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-university text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Transfer Bank</h3>
                    <p class="text-red-100 text-sm">BCA, BNI, Mandiri, BRI</p>
                </div>

                <!-- E-Wallet -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center hover:bg-white/20 transition transform hover:scale-105">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-wallet text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">E-Wallet</h3>
                    <p class="text-red-100 text-sm">GoPay, OVO, Dana, ShopeePay</p>
                </div>

                <!-- Debit/Kredit -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center hover:bg-white/20 transition transform hover:scale-105">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Kartu Debit/Kredit</h3>
                    <p class="text-red-100 text-sm">Visa, Mastercard, JCB</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="/login" class="bg-white text-red-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 hover:text-red-700 transition transform hover:scale-105 shadow-2xl inline-flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Kelola dengan Sistem POS
                </a>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-20 bg-gray-900 text-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-4xl font-bold mb-6">Hubungi Kami</h2>
                    <p class="text-gray-400 mb-8">Ada pertanyaan? Kami siap membantu Anda</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Alamat</h4>
                                <p class="text-gray-400">Jl. Raya Snack No. 123, Yogyakarta, Indonesia</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-phone text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Telepon</h4>
                                <p class="text-gray-400">+62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Email</h4>
                                <p class="text-gray-400">info@victorsnack.com</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Jam Operasional</h4>
                                <p class="text-gray-400">Senin - Sabtu: 08.00 - 20.00 WIB</p>
                                <p class="text-gray-400">Minggu: 09.00 - 17.00 WIB</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-gray-800 p-8 rounded-2xl">
                    <h3 class="text-2xl font-bold mb-6">Lokasi Kami</h3>
                    <div class="rounded-lg overflow-hidden mb-6">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56211042157!2d110.36069795820312!3d-7.797068299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5787bd5b6bc5%3A0x21723fd4d3684f71!2sYogyakarta%2C%20Kota%20Yogyakarta%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1234567890123!5m2!1sid!2sid" 
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-lg">
                        </iframe>
                    </div>
                    <div class="bg-red-900/20 border border-red-800 rounded-lg p-6">
                        <h4 class="font-bold text-lg mb-3 text-white">
                            <i class="fas fa-store mr-2 text-red-500"></i>Kunjungi Toko Kami
                        </h4>
                        <p class="text-gray-300 mb-4">
                            Datang langsung ke toko kami untuk mendapatkan produk segar dan penawaran spesial!
                        </p>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-map-marker-alt w-5 text-red-500"></i>
                                <span>Jl. Raya Snack No. 123, Yogyakarta</span>
                            </div>
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-clock w-5 text-red-500"></i>
                                <span>Senin - Sabtu: 08.00 - 20.00 WIB</span>
                            </div>
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-phone w-5 text-red-500"></i>
                                <span>+62 812-3456-7890</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Footer -->
<footer class="bg-gray-950 text-gray-400 py-8">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-3 mb-4 md:mb-0">
                <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cookie-bite text-white text-xl"></i>
                </div>
                <span class="text-xl font-bold text-white">Victor Snack</span>
            </div>
            <div class="text-center md:text-left">
                <p>&copy; 2025 Victor Snack. All rights reserved.</p>
                <p class="text-sm mt-1">Made with <i class="fas fa-heart text-red-500"></i> in Yogyakarta</p>
            </div>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-red-400 transition">Privacy Policy</a>
                <a href="#" class="hover:text-red-400 transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- Mobile Menu Toggle Script -->
<script>
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking nav links
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

</body>
</html>