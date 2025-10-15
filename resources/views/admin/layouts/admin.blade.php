<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="h-full">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('components.admin-menu')

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-auto">
            {{-- Top Navbar --}}
            @include('components.admin.nav')

            {{-- Page content --}}
            <main class="flex-1 p-6 bg-gray-100 dark:bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        themeToggle?.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    </script>

    @yield('scripts')
</body>
</html>
