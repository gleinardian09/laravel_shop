@php
    $modeController = new \App\Http\Controllers\ModeController();
    $currentMode = $modeController->getCurrentMode();
    $isAdminMode = $currentMode === 'admin';
    $isStoreMode = $currentMode === 'store';
    $user = Auth::user();
    $isAdminUser = $user && $user->is_admin;
@endphp

<nav x-data="{ open: false }" class="{{ $isAdminMode ? 'bg-indigo-600' : 'bg-blue-600' }} border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        @if($isAdminMode)
                            <div class="text-white text-xl font-bold flex items-center">
                                👑 <span class="ml-2">ShopApp Admin</span>
                            </div>
                        @else
                            <div class="text-white text-xl font-bold flex items-center">
                                🛍️ <span class="ml-2">ShopApp</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links - SIMPLIFIED: Only essential links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if($isStoreMode)
                        <!-- STORE MODE NAVIGATION -->
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white hover:text-blue-200 font-medium">
                            🏠 {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" class="text-white hover:text-blue-200 font-medium">
                            📦 {{ __('Products') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" class="text-white hover:text-blue-200 font-medium">
                            🛒 {{ __('Cart') }}
                            @php
                                $cartCount = 0;
                                if (auth()->check()) {
                                    $cartCount = auth()->user()->cartItems->sum('quantity');
                                } else {
                                    $cartCount = \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity');
                                }
                            @endphp
                            @if($cartCount > 0)
                                <span class="ml-1 bg-red-500 text-white rounded-full px-2 py-1 text-xs">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </x-nav-link>
                        @auth
                            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-white hover:text-blue-200 font-medium">
                                📋 {{ __('My Orders') }}
                            </x-nav-link>
                        @endauth
                    @else
                        <!-- ADMIN MODE NAVIGATION - ONLY DASHBOARD LINK -->
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-indigo-200 font-medium">
                            📊 {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                <!-- Search Bar (Store Mode Only) -->
                @if($isStoreMode)
                    <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                        <div class="relative">
                            <input type="text" name="q" 
                                   class="w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10 pr-4 py-2"
                                   placeholder="Search products..." value="{{ request('q') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">🔍</span>
                            </div>
                        </div>
                    </form>
                @endif

                <!-- Mode Indicator -->
                <div class="text-white font-semibold px-3 py-2 rounded-lg {{ $isAdminMode ? 'bg-indigo-700' : 'bg-blue-700' }}">
                    @if($isAdminMode)
                        👑 Admin Mode
                    @else
                        🛍️ Store Mode
                    @endif
                </div>

                <!-- Mode Switch Buttons -->
                @if($isAdminUser)
                    @if($isStoreMode)
                        <form method="POST" action="{{ route('switch.to.admin') }}">
                            @csrf
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-200">
                                👑 Admin Panel
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('switch.to.store') }}">
                            @csrf
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-200">
                                🛍️ Back to Store
                            </button>
                        </form>
                    @endif
                @endif

                <!-- Settings Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white {{ $isAdminMode ? 'bg-indigo-700 hover:bg-indigo-600' : 'bg-blue-700 hover:bg-blue-600' }} focus:outline-none transition ease-in-out duration-150">
                                <div>👤 {{ $user->name ?? 'Guest' }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if($isAdminMode && $isAdminUser)
                                <x-dropdown-link :href="route('admin.dashboard')" class="flex items-center text-gray-700 hover:bg-gray-100">
                                    👑 {{ __('Admin Dashboard') }}
                                </x-dropdown-link>
                            @endif
                            
                            @auth
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center text-gray-700 hover:bg-gray-100">
                                    ⚙️ {{ __('Profile Settings') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="flex items-center text-gray-700 hover:bg-gray-100">
                                        🚪 {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            @else
                                <x-dropdown-link :href="route('login')" class="flex items-center text-gray-700 hover:bg-gray-100">
                                    🔐 {{ __('Log In') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('register')" class="flex items-center text-gray-700 hover:bg-gray-100">
                                    📝 {{ __('Register') }}
                                </x-dropdown-link>
                            @endauth
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 hover:bg-opacity-20 hover:bg-white focus:outline-none focus:bg-opacity-20 focus:bg-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b">
        <div class="pt-2 pb-3 space-y-1">
            @if($isStoreMode)
                <!-- STORE MODE MOBILE NAVIGATION -->
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    🏠 {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                    📦 {{ __('Products') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    🛒 {{ __('Cart') }}
                    @if($cartCount > 0)
                        <span class="ml-1 bg-red-500 text-white rounded-full px-2 py-1 text-xs">
                            {{ $cartCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        📋 {{ __('My Orders') }}
                    </x-responsive-nav-link>
                @endauth
            @else
                <!-- ADMIN MODE MOBILE NAVIGATION - ONLY DASHBOARD -->
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    📊 {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">👤 {{ $user->name ?? 'Guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Mode Switch Mobile -->
                @if($isAdminUser)
                    @if($isStoreMode)
                        <form method="POST" action="{{ route('switch.to.admin') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-amber-50 border border-amber-200 rounded mx-3 mb-2">
                                👑 Switch to Admin Panel
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('switch.to.store') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bg-emerald-50 border border-emerald-200 rounded mx-3 mb-2">
                                🛍️ Back to Store
                            </button>
                        </form>
                    @endif
                @endif

                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">
                        ⚙️ {{ __('Profile Settings') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            🚪 {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        🔐 {{ __('Log In') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        📝 {{ __('Register') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
</nav>