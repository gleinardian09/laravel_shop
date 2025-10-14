<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                <input type="text" name="name" id="name" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Enter product name">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('price', $product->price) }}"
                                       placeholder="0.00">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stock *</label>
                                <input type="number" name="stock" id="stock" min="0" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('stock', $product->stock) }}"
                                       placeholder="Enter stock quantity">
                                @error('stock')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                                <textarea name="description" id="description" rows="4" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Multiple Images Section -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-4">Product Images</label>
                                
                                <!-- Current Images -->
                                @if($product->images->count() > 0)
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Current Images</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                            @foreach($product->images as $image)
                                                <div class="relative border-2 rounded-lg p-3 {{ $image->is_primary ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="w-full h-32 object-cover rounded mb-2">
                                                    <div class="space-y-2">
                                                        <label class="flex items-center justify-center">
                                                            <input type="radio" name="primary_image" value="{{ $image->id }}"
                                                                   {{ $image->is_primary ? 'checked' : '' }}
                                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm {{ $image->is_primary ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                                                                Primary
                                                            </span>
                                                        </label>
                                                        <label class="flex items-center justify-center">
                                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                            <span class="ml-2 text-sm text-red-600">Delete</span>
                                                        </label>
                                                    </div>
                                                    @if($image->is_primary)
                                                        <div class="absolute top-2 right-2">
                                                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Primary</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-gray-500 text-sm">No images uploaded yet.</p>
                                    </div>
                                @endif

                                <!-- New Images Upload -->
                                <div class="border-t pt-6">
                                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                                    <input type="file" name="images[]" id="images" multiple 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           accept="image/*">
                                    <p class="text-sm text-gray-500 mt-2">
                                        Select multiple images. Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB each). 
                                        The first image will be set as primary if no primary is selected.
                                    </p>
                                    @error('images')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Active Product
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.products.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>