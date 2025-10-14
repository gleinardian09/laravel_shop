<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Order History</h3>

                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                        <div class="mb-4 md:mb-0">
                                            <h4 class="font-semibold text-lg text-gray-900">Order #{{ $order->order_number }}</h4>
                                            <p class="text-gray-600 text-sm">Placed on {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4">
                                            <span class="text-xl font-bold text-green-600">${{ number_format($order->total_amount, 2) }}</span>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold 
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

                                    <div class="border-t pt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <h5 class="font-semibold text-gray-700 mb-2">Items Ordered</h5>
                                                <ul class="space-y-2">
                                                    @foreach($order->items as $item)
                                                        <li class="flex justify-between text-sm">
                                                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                                                            <span>${{ number_format($item->total_price, 2) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-700 mb-2">Shipping Address</h5>
                                                <p class="text-sm text-gray-600 whitespace-pre-line">{{ Str::limit($order->shipping_address, 100) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                📄 View Details
                                            </a>
                                            <form action="{{ route('orders.reorder', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    🛒 Reorder
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📦</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Orders Yet</h3>
                        <p class="text-gray-600 mb-8">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                        <a href="{{ route('products.index') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                            🛍️ Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>