@php
    $modeController = new \App\Http\Controllers\ModeController();
    $currentMode = $modeController->getCurrentMode();
    $isAdminMode = $currentMode === 'admin';
    $isStoreMode = $currentMode === 'store';
    $user = Auth::user();
    $isAdminUser = $user && $user->is_admin;
@endphp

<nav x-data="{ open: false }" class="{{ $isAdminMode ? 'bg-gradient-to-r from-purple-600 to-indigo-600' : 'bg-gradient-to-r from-blue-500 to-blue-700' }} shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-white font-bold text-xl space-x-2">
                    @if($isAdminMode)
                        👑 <span>ShopApp Admin</span>
                    @else
                        🛍️ <span>ShopApp</span>
                    @endif
                </a>
            </div>

            <!-- Links -->
            <div class="hidden sm:flex sm:space-x-6">
                @php
                    $cartCount = 0;
                    if (auth()->check()) {
                        $cartCount = auth()->user()->cartItems->sum('quantity');
                    } else {
                        $cartCount = \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity');
                    }
                @endphp

                @if($isStoreMode)
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white hover:text-gray-200 font-medium">
                        🏠 Home
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" class="text-white hover:text-gray-200 font-medium">
                        📦 Products
                    </x-nav-link>
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" class="text-white hover:text-gray-200 font-medium relative">
                        🛒 Cart
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full px-2 py-1 text-xs animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-white hover:text-gray-200 font-medium">
                            📋 My Orders
                        </x-nav-link>
                    @endauth
                @else
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-gray-200 font-medium">
                        📊 Dashboard
                    </x-nav-link>
                @endif
            </div>

            <!-- Right side - UPDATED WITH AUTH LINKS -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Mode Indicator -->
                <div class="px-3 py-2 rounded-lg text-white font-semibold {{ $isAdminMode ? 'bg-purple-700' : 'bg-blue-800' }}">
                    {{ $isAdminMode ? '👑 Admin Mode' : '🛍️ Store Mode' }}
                </div>

                <!-- Authentication Links -->
                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center space-x-2 text-white hover:text-gray-200 focus:outline-none transition duration-150"
                        >
                            <span class="font-medium">👤 {{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                            style="display: none;"
                        >
                            <x-responsive-nav-link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                ⚙️ Profile Settings
                            </x-responsive-nav-link>
                            
                            @if($isAdminUser)
                                <form method="POST" action="{{ $isStoreMode ? route('switch.to.admin') : route('switch.to.store') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $isStoreMode ? '👑 Admin Panel' : '🛍️ Store' }}
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    🚪 Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Links -->
                    <div class="flex items-center space-x-3">
                        <x-nav-link :href="route('login')" class="text-white hover:text-gray-200 font-medium border border-white px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-200">
                            🔐 Log In
                        </x-nav-link>
                        <x-nav-link :href="route('register')" class="bg-white text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-lg shadow transition duration-200">
                            📝 Register
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b">
        <div class="pt-2 pb-3 space-y-1">
            @if($isStoreMode)
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">🏠 Home</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">📦 Products</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    🛒 Cart
                    @if($cartCount > 0)
                        <span class="ml-1 bg-red-500 text-white rounded-full px-2 py-1 text-xs">{{ $cartCount }}</span>
                    @endif
                </x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">📋 My Orders</x-responsive-nav-link>
                @endauth
            @else
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">📊 Dashboard</x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">👤 {{ $user->name ?? 'Guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if($isAdminUser)
                    <form method="POST" action="{{ $isStoreMode ? route('switch.to.admin') : route('switch.to.store') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-gray-50 border rounded">Switch Mode</button>
                    </form>
                @endif

                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">⚙️ Profile Settings</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">🚪 Log Out</x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">🔐 Log In</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">📝 Register</x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
</nav>