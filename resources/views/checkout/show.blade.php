<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-6">Shipping & Billing Information</h3>
                    
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Shipping Address -->
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Shipping Address *
                                </label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Enter your complete shipping address">{{ old('shipping_address', auth()->user()->name . "\n" . 'Your Street Address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Billing Address -->
                            <div>
                                <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Billing Address *
                                </label>
                                <textarea name="billing_address" id="billing_address" rows="3" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Enter your complete billing address">{{ old('billing_address', auth()->user()->name . "\n" . 'Your Street Address') }}</textarea>
                                @error('billing_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number *
                                </label>
                                <input type="text" name="customer_phone" id="customer_phone" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('customer_phone') }}"
                                       placeholder="Enter your phone number">
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Order Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Any special instructions or notes for your order">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex space-x-4">
                                <a href="{{ route('cart.index') }}" 
                                   class="flex-1 bg-gray-500 hover:bg-gray-700 text-white text-center font-bold py-3 px-6 rounded-lg">
                                    Back to Cart
                                </a>
                                <button type="submit" 
                                        class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-2xl font-bold mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center space-x-3">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                        <p class="text-gray-600 text-sm">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold">
                                    ${{ number_format($item->quantity * $item->product->price, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold">Subtotal:</span>
                            <span class="text-lg font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="text-gray-600">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Tax:</span>
                            <span class="text-gray-600">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold mt-4 pt-4 border-t">
                            <span>Total:</span>
                            <span class="text-green-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>