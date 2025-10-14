<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">🛍️ Welcome to Store Mode, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mb-6">You're logged in and ready to shop. Here are some quick actions:</p>
                    
                    @if(Auth::user()->isAdmin())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-yellow-800 font-semibold">👑 You have administrator access.</p>
                            <p class="text-yellow-700 text-sm mt-1">Switch to Admin Mode to manage your store.</p>
                            <form method="POST" action="{{ route('switch.to.admin') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                    👑 Switch to Admin Panel
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-300 flex flex-col items-center">
                            <span class="text-2xl mb-2">📦</span>
                            <span>Browse Products</span>
                        </a>
                        
                        <a href="{{ route('cart.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-300 flex flex-col items-center">
                            <span class="text-2xl mb-2">🛒</span>
                            <span>View Cart</span>
                            @php
                                $cartCount = 0;
                                if (auth()->check()) {
                                    $cartCount = auth()->user()->cartItems->sum('quantity');
                                }
                            @endphp
                            @if($cartCount > 0)
                                <span class="mt-1 bg-red-500 text-white rounded-full px-2 py-1 text-xs">
                                    {{ $cartCount }} items
                                </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('orders.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-300 flex flex-col items-center">
                            <span class="text-2xl mb-2">📋</span>
                            <span>My Orders</span>
                            @php
                                $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
                            @endphp
                            @if($orderCount > 0)
                                <span class="mt-1 bg-blue-500 text-white rounded-full px-2 py-1 text-xs">
                                    {{ $orderCount }} orders
                                </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-300 flex flex-col items-center">
                            <span class="text-2xl mb-2">👤</span>
                            <span>Profile Settings</span>
                        </a>
                    </div>

                    <!-- Recent Orders Preview -->
                    @php
                        $recentOrders = \App\Models\Order::where('user_id', auth()->id())->latest()->take(3)->get();
                    @endphp
                    
                    @if($recentOrders->count() > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-800 mb-4">📦 Recent Orders</h4>
                            <div class="space-y-3">
                                @foreach($recentOrders as $order)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold">{{ $order->order_number }}</p>
                                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M j, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                                                <span class="px-2 py-1 rounded-full text-xs 
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(\App\Models\Order::where('user_id', auth()->id())->count() > 3)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        View All Orders →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>