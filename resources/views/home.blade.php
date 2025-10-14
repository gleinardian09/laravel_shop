<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to ShopApp') }}
        </h2>
    </x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center relative z-20">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6 text-white opacity-100 drop-shadow-2xl">
                    Discover Amazing Products
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-95 text-white drop-shadow">
                    Quality products at unbeatable prices. Shop with confidence.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="bg-white text-blue-700 hover:bg-white/95 font-bold py-3 px-8 rounded-lg text-lg shadow-md transition duration-300 transform hover:scale-105">
                        🛍️ Shop Now
                    </a>
                    <a href="#featured" 
                       class="border-2 border-white text-white hover:bg-white hover:text-blue-700 font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                        🔥 Featured Products
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-b from-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <span class="text-2xl">🚚</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-indigo-700">Free Shipping</h3>
                    <p class="text-gray-700">Free shipping on orders over $50</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <span class="text-2xl">🔒</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-green-700">Secure Payment</h3>
                    <p class="text-gray-700">100% secure payment processing</p>
                </div>
                <div class="text-center">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <span class="text-2xl">↩️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-pink-600">Easy Returns</h3>
                    <p class="text-gray-700">30-day money back guarantee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">🔥 Featured Products</h2>
                <p class="text-gray-600 text-lg">Handpicked items just for you</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-700 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                                <a href="{{ route('products.show', $product) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($featuredProducts->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-700 text-lg">No featured products available.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Advertisement Banner -->
    <section class="py-16 bg-gradient-to-r from-orange-400 to-pink-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">🎉 Summer Sale!</h2>
            <p class="text-xl mb-6">Up to 50% off on selected items. Limited time offer!</p>
            <a href="{{ route('products.index') }}" 
               class="bg-white text-orange-600 hover:bg-white/95 font-bold py-3 px-8 rounded-lg text-lg inline-block shadow-lg transition duration-300 transform hover:scale-105">
                Shop the Sale →
            </a>
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">🆕 New Arrivals</h2>
                <p class="text-gray-700 text-lg">Check out our latest products</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($newArrivals as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">NEW</span>
                            </div>
                            <p class="text-gray-700 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <!-- Shop by Category -->
@if(isset($categories) && $categories->count() > 0)
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">📁 Shop by Category</h2>
                <p class="text-gray-600 text-lg">Find what you're looking for</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                            <span class="text-2xl">📦</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $category->products_count }} products</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@else
    <!-- Fallback if no categories exist -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">📁 Shop by Category</h2>
            <p class="text-gray-600 mb-6">Categories coming soon!</p>
            <a href="{{ route('products.index') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                Browse All Products
            </a>
        </div>
    </section>
@endif

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-violet-900 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold mb-4">📧 Stay Updated</h2>
            <p class="text-violet-200 mb-6">Subscribe to our newsletter for the latest updates and exclusive offers.</p>
            <form class="max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="Enter your email" 
                       class="flex-1 px-4 py-3 rounded-lg border border-indigo-600 bg-white/5 text-white placeholder-violet-200 focus:outline-none focus:border-white">
                <button type="submit" 
                        class="bg-yellow-400 hover:bg-yellow-500 text-indigo-900 font-bold py-3 px-6 rounded-lg transition duration-300">
                    Subscribe
                </button>
            </form>
        </div>
    </section>
</x-app-layout>