@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Ganti Password</h1>
            <p class="text-gray-600 mt-1">Perbarui password Anda secara berkala untuk keamanan akun</p>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Alert Error -->
        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 animate-fade-in">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p class="text-sm font-medium">Terjadi kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-sm ml-8">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Password Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Tips Keamanan Password:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Gunakan minimal 6 karakter</li>
                            <li>Kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                            <li>Hindari menggunakan informasi pribadi</li>
                            <li>Jangan gunakan password yang sama di aplikasi lain</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route(role_route('password.update')) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <!-- Password Lama -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Lama <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('current_password') border-red-500 @enderror" 
                                   placeholder="Masukkan password lama"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('current_password', 'toggleIconCurrent')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIconCurrent"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('new_password') border-red-500 @enderror" 
                                   placeholder="Masukkan password baru"
                                   required
                                   onkeyup="checkPasswordStrength()">
                            <button type="button" 
                                    onclick="togglePassword('new_password', 'toggleIconNew')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIconNew"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600">Kekuatan Password:</span>
                                <span id="strengthText" class="font-semibold"></span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strengthBar" class="h-full transition-all duration-300 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation" 
                                   class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Ulangi password baru"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('new_password_confirmation', 'toggleIconConfirm')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route(role_route('dashboard')) }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                        <i class="fas fa-save mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check password strength
function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    let width = (strength / 5) * 100;
    strengthBar.style.width = width + '%';
    
    if (strength <= 2) {
        strengthBar.style.backgroundColor = '#ef4444'; // red
        strengthText.textContent = 'Lemah';
        strengthText.style.color = '#ef4444';
    } else if (strength <= 3) {
        strengthBar.style.backgroundColor = '#f59e0b'; // orange
        strengthText.textContent = 'Sedang';
        strengthText.style.color = '#f59e0b';
    } else if (strength <= 4) {
        strengthBar.style.backgroundColor = '#3b82f6'; // blue
        strengthText.textContent = 'Baik';
        strengthText.style.color = '#3b82f6';
    } else {
        strengthBar.style.backgroundColor = '#10b981'; // green
        strengthText.textContent = 'Sangat Kuat';
        strengthText.style.color = '#10b981';
    }
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection