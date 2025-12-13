<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Victor Snack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    
    <div class="min-h-screen flex">
        <!-- Left Side - Illustration with Gradient -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-red-600 via-red-500 to-rose-600 p-12 items-center justify-center relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>
            
            <!-- Floating Shapes -->
            <div class="absolute top-10 right-10 w-20 h-20 border-4 border-white/30 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-32 left-16 w-16 h-16 border-4 border-white/30 rounded-full animate-float-delayed"></div>
            <div class="absolute top-1/3 left-1/4 w-12 h-12 bg-white/20 rounded-lg rotate-45 animate-float-slow"></div>
            
            <div class="relative z-10 text-center max-w-md">
                <!-- Logo Victor Snack -->
                <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                    <div class="inline-flex items-center justify-center w-40 h-40 bg-white rounded-3xl shadow-2xl p-4 backdrop-blur-sm">
                        <img src="img/logo.PNG" alt="Victor Snack Logo" class="w-full h-full object-contain">
                    </div>
                </div>
                
                <h2 class="text-5xl font-bold text-white mb-4 tracking-tight">Victor Snack</h2>
                <p class="text-white/90 text-xl mb-8 font-light">Sistem Manajemen Toko Snack</p>
                
                <!-- Features -->
                <div class="space-y-4 text-white/80 text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="text-sm">Laporan Real-time</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-sm">Manajemen Inventory</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="text-sm">Kelola Tim & Akses</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center bg-white relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
            
            <div class="relative z-10 max-w-md mx-auto w-full">
                <!-- Logo Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-600 to-rose-600 rounded-2xl shadow-lg mb-3 p-2">
                        <img src="img/logo.PNG" alt="Victor Snack Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">Victor Snack</h1>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-4xl font-bold text-gray-900">Selamat Datang</h2>
                        <div class="w-12 h-1 bg-gradient-to-r from-red-600 to-rose-600 rounded-full"></div>
                    </div>
                    <p class="text-gray-600 text-lg mb-4">Silakan masuk ke akun Anda</p>
                </div>

                <!-- Alert Success -->
                @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-6 animate-slide-in shadow-sm">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Alert Error -->
                @if($errors->has('login'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600 text-red-800 p-4 rounded-xl mb-6 animate-slide-in shadow-sm">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation text-white text-sm"></i>
                        </div>
                        <p class="text-sm font-medium">{{ $errors->first('login') }}</p>
                    </div>
                </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div class="group">
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                            </div>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   value="{{ old('username') }}"
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600/20 focus:border-red-600 focus:bg-white transition-all duration-200 @error('username') border-red-400 @enderror" 
                                   placeholder="Masukkan username"
                                   required
                                   autofocus>
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600/20 focus:border-red-600 focus:bg-white transition-all duration-200 @error('password') border-red-400 @enderror" 
                                   placeholder="Masukkan password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 transition-colors">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Button Login -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Atau</span>
                        </div>
                    </div>

                    <!-- Additional Options -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Butuh bantuan? 
                            <a href="#" class="text-red-600 hover:text-red-700 font-semibold transition-colors">Hubungi Admin</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-slide-in');
            alerts.forEach(alert => {
                alert.style.transition = 'all 0.5s ease-out';
                alert.style.transform = 'translateX(100%)';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    <style>
        @keyframes slide-in {
            from { 
                opacity: 0; 
                transform: translateX(-20px); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(12deg); }
            50% { transform: translateY(-20px) rotate(12deg); }
        }
        
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) rotate(45deg); }
            50% { transform: translateY(-25px) rotate(45deg); }
        }
        
        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-float-delayed {
            animation: float-delayed 4s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        .animate-float-slow {
            animation: float-slow 5s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        
        /* Input focus effect */
        input:focus {
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }
        
        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</body>
</html>