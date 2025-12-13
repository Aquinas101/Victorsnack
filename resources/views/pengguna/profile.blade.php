@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Kelola informasi profil Anda</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <!-- Avatar -->
                        <div class="w-24 h-24 bg-gradient-to-br from-red-600 to-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            @php
                                $nama = explode(' ', Auth::user()->nama_lengkap);
                                $inisial = substr($nama[0], 0, 1) . (isset($nama[1]) ? substr($nama[1], 0, 1) : '');
                            @endphp
                            <span class="text-white text-3xl font-bold">{{ strtoupper($inisial) }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ Auth::user()->nama_lengkap }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ '@' . Auth::user()->username }}</p>
                        
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-600 text-sm font-semibold rounded-full">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>

                        <div class="mt-6 pt-6 border-t border-gray-200 text-left space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                <span class="ml-2">Bergabung sejak {{ \Carbon\Carbon::parse(Auth::user()->create_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Profil</h2>

                    <form action="{{ route(role_route('profile.update')) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="nama_lengkap" 
                                           id="nama_lengkap" 
                                           value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama_lengkap') border-red-500 @enderror" 
                                           required>
                                </div>
                                @error('nama_lengkap')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Username
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="username" 
                                           id="username" 
                                           value="{{ old('username', Auth::user()->username) }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('username') border-red-500 @enderror" 
                                           required>
                                </div>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tempat Lahir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="tempat_lahir" 
                                           id="tempat_lahir" 
                                           value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('tempat_lahir') border-red-500 @enderror">
                                </div>
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="tanggal_lahir" 
                                           id="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('tanggal_lahir') border-red-500 @enderror">
                                </div>
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role (Read Only) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role / Jabatan
                                </label>
                                <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                                    {{ ucfirst(Auth::user()->role) }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Role tidak dapat diubah sendiri</p>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route(role_route('dashboard')) }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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