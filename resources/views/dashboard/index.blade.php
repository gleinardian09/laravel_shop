<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Orders -->
                <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-blue-800">📦 Total Orders</h4>
                        <span class="text-2xl">📦</span>
                    </div>
                    <p class="text-3xl font-bold text-blue-600 mb-2">{{ $totalOrders }}</p>
                    <p class="text-blue-600 text-sm">All time purchases</p>
                </div>

                <!-- Pending Orders -->
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-yellow-800">⏳ Pending Orders</h4>
                        <span class="text-2xl">⏳</span>
                    </div>
                    <p class="text-3xl font-bold text-yellow-600 mb-2">{{ $pendingOrders }}</p>
                    <p class="text-yellow-600 text-sm">Awaiting fulfillment</p>
                </div>

                <!-- Total Spent -->
                <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-green-800">💰 Total Spent</h4>
                        <span class="text-2xl">💰</span>
                    </div>
                    <p class="text-3xl font-bold text-green-600 mb-2">${{ number_format($totalSpent, 2) }}</p>
                    <p class="text-green-600 text-sm">Lifetime value</p>
                </div>

                <!-- My Reviews -->
                <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-purple-800">⭐ My Reviews</h4>
                        <span class="text-2xl">⭐</span>
                    </div>
                    <p class="text-3xl font-bold text-purple-600 mb-2">{{ $recentReviews->count() }}</p>
                    <p class="text-purple-600 text-sm">Product reviews</p>
                </div>
            </div>

            <!-- Recent Orders & Reviews -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Recent Orders</h3>
                        @if($recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700">Total: ${{ number_format($order->total_amount, 2) }}</p>
                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                            View Details →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    View All Orders →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">📦</div>
                                <p class="text-gray-600">No orders yet.</p>
                                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Start Shopping →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">⭐ My Recent Reviews</h3>
                        @if($recentReviews->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentReviews as $review)
                                    <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <p class="font-semibold text-gray-900">{{ $review->product->name }}</p>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400">★</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($review->comment, 100) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $review->created_at->format('M j, Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">⭐</div>
                                <p class="text-gray-600">No reviews yet.</p>
                                <p class="text-sm text-gray-500 mt-1">Review products you've purchased!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            🛍️ Continue Shopping
                        </a>
                        <a href="{{ route('orders.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            📋 View All Orders
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            👤 Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>