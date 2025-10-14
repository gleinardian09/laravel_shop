<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold">{{ $category->name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-700">Description</h4>
                                    <p class="text-gray-600">{{ $category->description ?: 'No description provided.' }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Slug</h4>
                                        <p class="text-gray-600">{{ $category->slug }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Total Products</h4>
                                        <p class="text-gray-600">{{ $category->products->count() }}</p>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-700">Created</h4>
                                    <p class="text-gray-600">{{ $category->created_at->format('M d, Y H:i') }}</p>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-700">Last Updated</h4>
                                    <p class="text-gray-600">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($category->products->count() > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-700 mb-4">Products in this Category</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b">Name</th>
                                            <th class="py-2 px-4 border-b">Price</th>
                                            <th class="py-2 px-4 border-b">Stock</th>
                                            <th class="py-2 px-4 border-b">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td class="py-2 px-4 border-b">
                                                    <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-800">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td class="py-2 px-4 border-b">${{ number_format($product->price, 2) }}</td>
                                                <td class="py-2 px-4 border-b">{{ $product->stock }}</td>
                                                <td class="py-2 px-4 border-b">
                                                    <span class="px-2 py-1 rounded-full text-xs {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @else
                        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">No products found in this category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>