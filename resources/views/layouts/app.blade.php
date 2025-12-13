<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

</body>
</html>