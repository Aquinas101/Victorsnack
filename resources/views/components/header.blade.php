<header class="bg-white shadow-sm border-b border-gray-200 flex justify-between items-center px-6 py-4 sticky top-0 z-10">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-user-shield text-white text-sm"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                {{ ucfirst(Auth::user()->role) }} Dashboard
            </h1>
            <p class="text-xs text-gray-500">{{ Auth::user()->nama_lengkap }}</p>
        </div>
    </div>

    <div class="flex items-center">
        <!-- Profile Dropdown -->
        <div class="relative">
            <button onclick="toggleDropdown()" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center">
                    @php
                        $nama = explode(' ', Auth::user()->nama_lengkap);
                        $inisial = substr($nama[0], 0, 1) . (isset($nama[1]) ? substr($nama[1], 0, 1) : '');
                    @endphp
                    <span class="text-white text-sm font-semibold">{{ strtoupper($inisial) }}</span>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1">
                <!-- Profil -->
                <a href="{{ route(role_route('profile')) }}" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition">
                    <i class="fas fa-user text-gray-600 w-5"></i>
                    <span class="ml-3 text-sm text-gray-700">Profil</span>
                </a>

                <!-- Ganti Password -->
                <a href="{{ route(role_route('password.change')) }}" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition">
                    <i class="fas fa-key text-gray-600 w-5"></i>
                    <span class="ml-3 text-sm text-gray-700">Ganti Password</span>
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2.5 hover:bg-red-50 transition text-left">
                        <i class="fas fa-sign-out-alt text-red-600 w-5"></i>
                        <span class="ml-3 text-sm text-red-600 font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    const button = event.target.closest('button[onclick="toggleDropdown()"]');
    
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<style>
#dropdownMenu {
    animation: fadeIn 0.15s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>