<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>

                    <!-- Order Status -->
                    <div class="mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
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

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                                    <div class="flex items-center space-x-4">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                            <p class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        <p class="text-gray-600 text-sm">${{ number_format($item->price, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg border-t border-gray-200 pt-2">
                                <span>Total:</span>
                                <span>${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="whitespace-pre-wrap">{{ $order->shipping_address }}</pre>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-4">
                        <a href="{{ route('orders.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            ← Back to Orders
                        </a>
                        
                        @if($order->status === 'completed')
                            <form action="{{ route('orders.reorder', $order) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                    🔄 Reorder Items
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>