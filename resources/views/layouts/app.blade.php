<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine JS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content Wrapper --}}
    <div class="md:ml-64">

        {{-- Header --}}
        @include('components.header')

        {{-- Main Content --}}
        <main class="p-6 min-h-screen">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('components.footer')
    </div>

    <!-- ⬇️ WAJIB ADA (INI KUNCI GRAFIK MUNCUL) -->
    @stack('scripts')

</body>
</html>
