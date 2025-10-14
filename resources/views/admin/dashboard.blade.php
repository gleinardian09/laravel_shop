<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Welcome Section -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">👑 Welcome to Admin Panel</h3>
                        <p class="text-gray-600">Manage your store, products, categories, and customer orders from one central dashboard.</p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-indigo-800">📦 Products</h4>
                                <span class="text-2xl">📦</span>
                            </div>
                            <p class="text-3xl font-bold text-indigo-600 mb-2">{{ \App\Models\Product::count() }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                    Manage Products →
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-green-800">📁 Categories</h4>
                                <span class="text-2xl">📁</span>
                            </div>
                            <p class="text-3xl font-bold text-green-600 mb-2">{{ \App\Models\Category::count() }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                    Manage Categories →
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-purple-800">📋 Orders</h4>
                                <span class="text-2xl">📋</span>
                            </div>
                            <p class="text-3xl font-bold text-purple-600 mb-2">{{ \App\Models\Order::count() }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.orders.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                                    Manage Orders →
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-amber-50 border border-amber-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-amber-800">👥 Users</h4>
                                <span class="text-2xl">👥</span>
                            </div>
                            <p class="text-3xl font-bold text-amber-600 mb-2">{{ \App\Models\User::count() }}</p>
                            <span class="text-amber-600 text-sm font-medium">Total Users</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-800 mb-4 text-lg">🚀 Quick Actions</h4>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.products.create') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                                ➕ Add New Product
                            </a>
                            <a href="{{ route('admin.categories.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                                ➕ Add New Category
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                                📋 Manage Orders
                            </a>
                            <form method="POST" action="{{ route('switch.to.store') }}">
                                @csrf
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                                    🛍️ Back to Store
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Orders Section -->
                    @php
                        $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();
                    @endphp
                    
                    @if($recentOrders->count() > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg">📦 Recent Orders</h4>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <div class="space-y-4">
                                    @foreach($recentOrders as $order)
                                        <div class="flex justify-between items-center border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                                <p class="text-sm text-gray-600">{{ $order->user->name }} • {{ $order->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if($order->status == 'pending') ⏳ Pending
                                                    @elseif($order->status == 'processing') 🔄 Processing
                                                    @elseif($order->status == 'completed') ✅ Completed
                                                    @else ❌ Cancelled @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if(\App\Models\Order::count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-lg">
                                            View All Orders →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-8 bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
                            <div class="text-4xl mb-2">📦</div>
                            <p class="text-amber-800 font-medium text-lg">No orders yet.</p>
                            <p class="text-amber-700">Orders will appear here when customers make purchases.</p>
                        </div>
                    @endif

                    <!-- Quick Navigation Cards -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('admin.products.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-indigo-600">📦 Product Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📦</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage your product catalog, inventory, and pricing.</p>
                            <span class="text-indigo-600 font-medium group-hover:text-indigo-800">View Products →</span>
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-green-600">📁 Category Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📁</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Organize products into categories for better navigation.</p>
                            <span class="text-green-600 font-medium group-hover:text-green-800">View Categories →</span>
                        </a>

                        <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-purple-600">📋 Order Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📋</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Process customer orders and update order status.</p>
                            <span class="text-purple-600 font-medium group-hover:text-purple-800">View Orders →</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>