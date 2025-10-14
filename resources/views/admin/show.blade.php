<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold">{{ $product->name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400">No image available</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-700">Description</h4>
                                    <p class="text-gray-600">{{ $product->description }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Category</h4>
                                        <p class="text-gray-600">{{ $product->category->name }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Price</h4>
                                        <p class="text-gray-600">${{ number_format($product->price, 2) }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Stock</h4>
                                        <p class="text-gray-600">{{ $product->stock }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Status</h4>
                                        <span class="px-2 py-1 rounded-full text-xs {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-700">Slug</h4>
                                    <p class="text-gray-600">{{ $product->slug }}</p>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-700">Created</h4>
                                    <p class="text-gray-600">{{ $product->created_at->format('M d, Y H:i') }}</p>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-700">Last Updated</h4>
                                    <p class="text-gray-600">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>