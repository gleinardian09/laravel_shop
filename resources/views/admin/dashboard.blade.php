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
                        <!-- Products Card -->
                        <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-indigo-800">📦 Products</h4>
                                <span class="text-2xl">📦</span>
                            </div>
                            <p class="text-3xl font-bold text-indigo-600 mb-2">{{ $totalProducts }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                    Manage Products →
                                </a>
                            </div>
                        </div>
                        
                        <!-- Categories Card -->
                        <div class="bg-green-50 border border-green-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-green-800">📁 Categories</h4>
                                <span class="text-2xl">📁</span>
                            </div>
                            <p class="text-3xl font-bold text-green-600 mb-2">{{ $totalCategories }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                    Manage Categories →
                                </a>
                            </div>
                        </div>

                        <!-- Subcategories Card -->
                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-blue-800">📑 Subcategories</h4>
                                <span class="text-2xl">📑</span>
                            </div>
                            <p class="text-3xl font-bold text-blue-600 mb-2">{{ $totalSubcategories }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.subcategories.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    Manage Subcategories →
                                </a>
                            </div>
                        </div>
                        
                        <!-- Orders Card -->
                        <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-purple-800">📋 Orders</h4>
                                <span class="text-2xl">📋</span>
                            </div>
                            <p class="text-3xl font-bold text-purple-600 mb-2">{{ $totalOrders }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.orders.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                                    Manage Orders →
                                </a>
                            </div>
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
                            <a href="{{ route('admin.subcategories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                                ➕ Add New Subcategory
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
                    @if($recentOrders->count() > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg">📦 Recent Orders</h4>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <div class="space-y-4">
                                    @foreach($recentOrders as $order)
                                        <div class="flex justify-between items-center border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $order->order_number ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">
                                                    @if($order->user)
                                                        {{ $order->user->name }} • 
                                                    @else
                                                        Guest • 
                                                    @endif
                                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                                </p>
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
                                @if($totalOrders > 5)
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

                    <!-- Low Stock Alert -->
                    @if($lowStockProducts->count() > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg">⚠️ Low Stock Alert</h4>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                                <div class="space-y-3">
                                    @foreach($lowStockProducts->take(5) as $product)
                                        <div class="flex justify-between items-center border-b border-red-100 pb-3 last:border-b-0 last:pb-0">
                                            <div class="flex-1">
                                                <p class="font-semibold text-red-800">{{ $product->name }}</p>
                                                <p class="text-sm text-red-600">Current stock: {{ $product->stock }}</p>
                                            </div>
                                            <div class="text-right">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Restock
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($lowStockProducts->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('admin.products.index') }}" class="text-red-600 hover:text-red-800 font-semibold text-lg">
                                            View All Low Stock Items →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Quick Navigation Cards -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Products Management -->
                        <a href="{{ route('admin.products.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-indigo-600">📦 Product Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📦</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage your product catalog, inventory, and pricing.</p>
                            <span class="text-indigo-600 font-medium group-hover:text-indigo-800">View Products →</span>
                        </a>

                        <!-- Categories Management -->
                        <a href="{{ route('admin.categories.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-green-600">📁 Category Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📁</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Organize products into categories for better navigation.</p>
                            <span class="text-green-600 font-medium group-hover:text-green-800">View Categories →</span>
                        </a>

                        <!-- Subcategories Management -->
                        <a href="{{ route('admin.subcategories.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-blue-600">📑 Subcategory Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📑</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage subcategories to further organize your products.</p>
                            <span class="text-blue-600 font-medium group-hover:text-blue-800">View Subcategories →</span>
                        </a>

                        <!-- Orders Management -->
                        <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-200 p-6 rounded-lg hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800 group-hover:text-purple-600">📋 Order Management</h5>
                                <span class="text-2xl group-hover:scale-110 transition-transform">📋</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Process customer orders and update order status.</p>
                            <span class="text-purple-600 font-medium group-hover:text-purple-800">View Orders →</span>
                        </a>

                        <!-- Users Management -->
                        <div class="bg-white border border-gray-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800">👥 User Management</h5>
                                <span class="text-2xl">👥</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">View and manage user accounts and permissions.</p>
                            <span class="text-amber-600 font-medium">Total Users: {{ $totalUsers }}</span>
                        </div>

                        <!-- Analytics -->
                        <div class="bg-white border border-gray-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-gray-800">📊 Analytics</h5>
                                <span class="text-2xl">📊</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">View sales analytics and business insights.</p>
                            <span class="text-teal-600 font-medium">Coming Soon →</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>