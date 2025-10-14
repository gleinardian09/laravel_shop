<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔍 {{ __('Search Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Search Results</h1>
                            @if($search)
                                <p class="text-gray-600">Searching for: "<span class="font-semibold">{{ $search }}</span>"</p>
                            @endif
                            <p class="text-gray-600">{{ $totalResults }} products found</p>
                        </div>
                        
                        <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ← Back to All Products
                        </a>
                    </div>

                    <!-- Search and Filter Form -->
                    <form action="{{ route('search') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="q" id="q" value="{{ $search }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Search products...">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                                <input type="number" name="min_price" id="min_price" value="{{ $minPrice }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="0" min="0" step="0.01">
                            </div>

                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                                <input type="number" name="max_price" id="max_price" value="{{ $maxPrice }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="1000" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                            <!-- Sort Options -->
                            <div class="flex items-center space-x-4">
                                <label for="sort" class="text-sm font-medium text-gray-700">Sort by:</label>
                                <select name="sort" id="sort" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    🔍 Apply Filters
                                </button>
                                <a href="{{ route('search') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    🗑️ Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
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
                                
                                <!-- Rating -->
                                @if($product->rating_count > 0)
                                    <div class="flex items-center mb-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($product->approved_reviews_avg_rating))
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
                                @else
                                    <div class="text-sm text-gray-500 mb-2">No reviews yet</div>
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
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Products Found</h3>
                        <p class="text-gray-600 mb-8">Try adjusting your search criteria or browse all products.</p>
                        <a href="{{ route('products.index') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                            Browse All Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>