<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📄 {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                            <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-center md:text-right">
                            <span class="px-4 py-2 rounded-full text-lg font-semibold 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($order->status == 'pending') ⏳ Pending
                                @elseif($order->status == 'processing') 🔄 Processing  
                                @elseif($order->status == 'completed') ✅ Completed
                                @else ❌ Cancelled @endif
                            </span>
                            <p class="text-2xl font-bold text-green-600 mt-2">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>

                    <!-- Order Progress -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Order Status</h3>
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <div class="w-10 h-10 rounded-full {{ $order->status == 'pending' ? 'bg-yellow-500' : 'bg-gray-300' }} flex items-center justify-center mx-auto mb-2">
                                    @if($order->status == 'pending') 1 @endif
                                </div>
                                <span class="text-sm {{ $order->status == 'pending' ? 'font-semibold text-yellow-600' : 'text-gray-500' }}">Pending</span>
                            </div>
                            <div class="flex-1 h-1 {{ $order->status != 'pending' ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                            <div class="text-center">
                                <div class="w-10 h-10 rounded-full {{ $order->status == 'processing' ? 'bg-blue-500' : ($order->status == 'completed' ? 'bg-blue-500' : 'bg-gray-300') }} flex items-center justify-center mx-auto mb-2">
                                    @if(in_array($order->status, ['processing', 'completed'])) 2 @endif
                                </div>
                                <span class="text-sm {{ in_array($order->status, ['processing', 'completed']) ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Processing</span>
                            </div>
                            <div class="flex-1 h-1 {{ $order->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                            <div class="text-center">
                                <div class="w-10 h-10 rounded-full {{ $order->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center mx-auto mb-2">
                                    @if($order->status == 'completed') 3 @endif
                                </div>
                                <span class="text-sm {{ $order->status == 'completed' ? 'font-semibold text-green-600' : 'text-gray-500' }}">Completed</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->product->image)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}">
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping & Billing -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Shipping Address</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-line">{{ $order->shipping_address }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Billing Address</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-line">{{ $order->billing_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Phone:</strong> {{ $order->customer_phone ?? 'Not provided' }}</p>
                            @if($order->notes)
                                <p class="text-gray-700 mt-2"><strong>Notes:</strong> {{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-4 pt-6 border-t">
                        <a href="{{ route('orders.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            ← Back to Orders
                        </a>
                        <form action="{{ route('orders.reorder', $order) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                🛒 Reorder These Items
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            🛍️ Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>