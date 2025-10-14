<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($cartItems->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Your Cart</h3>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" 
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    Clear Cart
                                </button>
                            </form>
                        </div>

                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-4 border-b pb-6">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-sm">No image</span>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-lg">{{ $item->product->name }}</h4>
                                        <p class="text-gray-600 text-sm">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                                        <p class="text-green-600 font-bold">${{ number_format($item->product->price, 2) }}</p>
                                        <p class="text-sm text-gray-500">
                                            Stock: {{ $item->product->stock }}
                                        </p>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="quantity-btn bg-gray-200 rounded-l px-3 py-1" 
                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                                   class="w-16 text-center border-y border-gray-300 focus:ring-0">
                                            <button type="button" class="quantity-btn bg-gray-200 rounded-r px-3 py-1"
                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                                            <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                                Update
                                            </button>
                                        </form>

                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4">
                                                Remove
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-lg">
                                            ${{ number_format($item->quantity * $item->product->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-semibold">Total:</span>
                                <span class="text-2xl font-bold text-green-600">${{ number_format($total, 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('products.index') }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    ← Continue Shopping
                                </a>
                                
                                @auth
                                    <a href="{{ route('checkout.show') }}" 
                                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                        Proceed to Checkout
                                    </a>
                                @else
                                    <div class="text-center">
                                        <p class="text-gray-600 mb-2">Please login to checkout</p>
                                        <a href="{{ route('login') }}" 
                                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                            Login to Checkout
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🛒</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h3>
                        <p class="text-gray-600 mb-8">Start shopping to add items to your cart</p>
                        <a href="{{ route('products.index') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                            Browse Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Quantity button functionality
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input[type=number]');
                const max = parseInt(input.getAttribute('max'));
                const min = parseInt(input.getAttribute('min'));
                let value = parseInt(input.value);

                if (this.textContent === '+' && value < max) {
                    input.value = value + 1;
                } else if (this.textContent === '-' && value > min) {
                    input.value = value - 1;
                }
            });
        });
    </script>
</x-app-layout>