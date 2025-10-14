<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <div class="text-6xl mb-6">🎉</div>
                    <h1 class="text-3xl font-bold text-green-600 mb-4">Order Placed Successfully!</h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Thank you for your purchase. Your order has been received and is being processed.
                    </p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                        <p class="text-green-800 mb-2">
                            You will receive an order confirmation shortly.
                        </p>
                        <p class="text-green-800">
                            Our team will process your order and contact you if needed.
                        </p>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('products.index') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                            Continue Shopping
                        </a>
                        <a href="{{ route('home') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>