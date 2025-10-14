<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <nav class="flex mb-6" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    Home
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                        {{ $product->category->name ?? 'Products' }}
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div>
                            @if($product->image || $product->images->count() > 0)
                                @php
                                    $mainImage = $product->images->count() > 0 
                                        ? ($product->images->firstWhere('is_primary', true)?->image_path ?? $product->images->first()->image_path)
                                        : $product->image;
                                @endphp
                                <img src="{{ asset('storage/' . $mainImage) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full rounded-lg shadow-lg"
                                     style="image-rendering: high-quality;">
                            @else
                                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400 text-lg">No image available</span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                            
                            <div class="mb-4">
                                <span class="text-3xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                            </div>

                            <div class="mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">{{ $product->stock }} available</span>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Description</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                            </div>

                            <!-- Category Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3 text-gray-900">Category</h3>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}" 
                                       class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </a>
                                    @if($product->category && $product->category->products_count)
                                        <span class="text-xs text-gray-500">{{ $product->category->products_count }} products</span>
                                    @endif
                                </div>
                            </div>

                            @if($product->stock > 0)
                                <div class="flex items-center space-x-4 mb-6">
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <div class="flex items-center border border-gray-300 rounded mr-4">
                                            <button type="button" class="quantity-btn px-4 py-2 text-gray-600 hover:text-gray-700" 
                                                    onclick="this.nextElementSibling.stepDown()">-</button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                                   class="w-16 text-center border-0 focus:ring-0">
                                            <button type="button" class="quantity-btn px-4 py-2 text-gray-600 hover:text-gray-700"
                                                    onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button disabled class="bg-gray-400 text-white font-bold py-2 px-6 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    @include('products._reviews')

                    <!-- Related Products -->
                    @if($relatedProducts->count() > 0)
                        <div class="mt-12">
                            <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                                @foreach($relatedProducts as $relatedProduct)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                        @php
                                            $relatedImage = $relatedProduct->images->count() > 0 
                                                ? ($relatedProduct->images->firstWhere('is_primary', true)?->image_path ?? $relatedProduct->images->first()->image_path)
                                                : $relatedProduct->image;
                                        @endphp
                                        @if($relatedImage)
                                            <img src="{{ asset('storage/' . $relatedImage) }}" 
                                                 alt="{{ $relatedProduct->name }}" 
                                                 class="w-full h-40 object-cover"
                                                 style="image-rendering: high-quality;">
                                        @else
                                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-400">No image</span>
                                            </div>
                                        @endif
                                        
                                        <div class="p-4">
                                            <h3 class="font-semibold text-lg mb-2">{{ $relatedProduct->name }}</h3>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xl font-bold text-green-600">${{ number_format($relatedProduct->price, 2) }}</span>
                                                <a href="{{ route('products.show', $relatedProduct) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                    View →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>