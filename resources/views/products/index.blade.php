<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Our Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('search') }}" method="GET" class="flex space-x-4">
                        <div class="flex-1">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Search products by name, description, or category...">
                        </div>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                            🔍 Search
                        </button>
                        <a href="{{ route('search') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                            🔧 Advanced
                        </a>
                    </form>
                </div>
            </div>

            <!-- Rest of the existing products index content remains the same -->
            <!-- Categories Navigation -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Categories</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index') }}" 
                       class="px-4 py-2 rounded-full {{ request()->routeIs('home') || !request('category') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        All Products
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                           class="px-4 py-2 rounded-full {{ request('category') == $category->slug ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid (existing code remains) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->description, 80) }}</p>
                            
                            <!-- Add Rating Display -->
                            @if($product->rating_count > 0)
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->average_rating))
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600 ml-2">
                                        ({{ $product->rating_count }})
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                                <a href="{{ route('products.show', $product) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>

            @if($products->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No products found.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>