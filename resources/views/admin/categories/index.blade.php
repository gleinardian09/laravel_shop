<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">All Categories</h3>
                        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Category
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.categories.index') }}" class="space-y-4 md:space-y-0 md:flex md:space-x-4 md:items-end">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Categories</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by name, slug, or description..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Products Count Filter -->
                            <div class="flex-1">
                                <label for="products_count" class="block text-sm font-medium text-gray-700 mb-1">Products Filter</label>
                                <select name="products_count" 
                                        id="products_count"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                    <option value="no_products" {{ request('products_count') == 'no_products' ? 'selected' : '' }}>No Products</option>
                                    <option value="has_products" {{ request('products_count') == 'has_products' ? 'selected' : '' }}>Has Products</option>
                                    <option value="many_products" {{ request('products_count') == 'many_products' ? 'selected' : '' }}>5+ Products</option>
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div class="flex-1">
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" 
                                        id="sort"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    <option value="products_asc" {{ request('sort') == 'products_asc' ? 'selected' : '' }}>Fewest Products</option>
                                    <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Most Products</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Apply Filters
                                </button>
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Clear
                                </a>
                            </div>
                        </form>

                        <!-- Active Filters -->
                        @if(request()->anyFilled(['search', 'products_count', 'sort']))
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-sm text-gray-600">Active filters:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('products_count'))
                                    @php
                                        $productFilterLabels = [
                                            'no_products' => 'No Products',
                                            'has_products' => 'Has Products',
                                            'many_products' => '5+ Products'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Products: {{ $productFilterLabels[request('products_count')] ?? request('products_count') }}
                                        <a href="{{ request()->fullUrlWithQuery(['products_count' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort') && request('sort') != 'latest')
                                    @php
                                        $sortLabels = [
                                            'oldest' => 'Oldest',
                                            'name_asc' => 'Name A-Z',
                                            'name_desc' => 'Name Z-A',
                                            'products_asc' => 'Fewest Products',
                                            'products_desc' => 'Most Products'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Sort: {{ $sortLabels[request('sort')] ?? request('sort') }}
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Categories Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Name</th>
                                    <th class="py-2 px-4 border-b">Slug</th>
                                    <th class="py-2 px-4 border-b">Products</th>
                                    <th class="py-2 px-4 border-b">Description</th>
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="py-2 px-4 border-b font-semibold">{{ $category->name }}</td>
                                        <td class="py-2 px-4 border-b text-gray-600">{{ $category->slug }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="{{ $category->products_count == 0 ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                                {{ $category->products_count }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-gray-600">{{ Str::limit($category->description, 50) }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.categories.show', $category) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category) }}" class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm"
                                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 border-b text-center text-gray-500">
                                            @if(request()->anyFilled(['search', 'products_count']))
                                                No categories found matching your filters.
                                                <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800 ml-1">Clear filters</a>
                                            @else
                                                No categories found.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>