<div class="flex justify-between items-center bg-white dark:bg-gray-800 shadow p-4">
    {{-- Quick Search --}}
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex-1 max-w-md">
        <input type="text" name="q" placeholder="Search products, orders, categories..."
            class="w-full px-4 py-2 rounded border focus:ring focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
    </form>

    <div class="flex items-center space-x-4 ml-4">
        {{-- Notifications --}}
        <div class="relative">
            <button class="relative">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C8.67 6.165 8 7.388 8 8.75v5.408c0 .538-.214 1.055-.595 1.437L6 17h9z"></path>
                </svg>
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ $recentOrders->count() ?? 0 }}
                </span>
            </button>
        </div>

        {{-- Theme Toggle --}}
        <button id="theme-toggle" class="p-2 bg-gray-200 dark:bg-gray-700 rounded">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12.515h1m14.85 4.95l.707.707M4.929 5.343l.707.707M18.364 5.636l.707-.707M5.636 18.364l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
            </svg>
        </button>
    </div>
</div>
