<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                <input type="text" name="name" id="name" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('name') }}"
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
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                       value="{{ old('price') }}"
                                       placeholder="0.00">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stock *</label>
                                <input type="number" name="stock" id="stock" min="0" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('stock') }}"
                                       placeholder="Enter stock quantity">
                                @error('stock')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                                <textarea name="description" id="description" rows="4" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Multiple Images Section -->
<div class="md:col-span-2">
    <label for="images" class="block text-sm font-medium text-gray-700">Product Images *</label>
    <input type="file" name="images[]" id="images" multiple 
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
           accept="image/*"
           onchange="validateImages(this)">
    <p class="text-sm text-gray-500 mt-2">
        Select at least one image. The first image will be set as primary. 
        Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB each)
    </p>
    
    <!-- Display general images array error -->
    @error('images')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    
    <!-- Display individual image errors -->
    @if($errors->has('images.*'))
       <!-- Multiple Images Section -->
<div class="md:col-span-2">
    <label for="images" class="block text-sm font-medium text-gray-700">Product Images *</label>
    <input type="file" name="images[]" id="images" multiple 
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
           accept="image/*">
    <p class="text-sm text-gray-500 mt-2">
        Select at least one image. The first image will be set as primary. 
        Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB each)
    </p>
    
    <!-- Display general images array error -->
    @error('images')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    
    <!-- Display individual image errors -->
    @if($errors->has('images.*'))
        <div class="mt-2 space-y-1">
            @foreach($errors->get('images.*') as $key => $messages)
                @foreach($messages as $message)
                    <p class="text-red-500 text-sm">
                        • {{ $message }}
                    </p>
                @endforeach
            @endforeach
        </div>
    @endif
</div>
    @endif
    
    <!-- Image preview container -->
    <div id="imagePreview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 hidden"></div>
</div>

<div class="flex items-center">
    <input type="checkbox" name="is_active" id="is_active" value="1" 
           {{ old('is_active') ? 'checked' : '' }}
           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
    <label for="is_active" class="ml-2 block text-sm text-gray-700">
        Active Product
    </label>
</div>

<script>
function validateImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    previewContainer.classList.add('hidden');
    
    if (input.files.length > 0) {
        previewContainer.classList.remove('hidden');
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative border rounded-lg p-1';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-20 object-cover rounded';
                img.alt = 'Preview ' + (i + 1);
                
                const label = document.createElement('div');
                label.className = 'text-xs text-gray-500 text-center mt-1';
                label.textContent = `Image ${i + 1}`;
                
                if (i === 0) {
                    const badge = document.createElement('div');
                    badge.className = 'absolute top-1 left-1 bg-blue-500 text-white text-xs px-1 rounded';
                    badge.textContent = 'Primary';
                    div.appendChild(badge);
                }
                
                div.appendChild(img);
                div.appendChild(label);
                previewContainer.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }
    }
}

// Client-side validation for file size and type
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            alert(`Error: ${file.name} is not a supported image format. Please use JPEG, PNG, JPG, GIF, or WEBP.`);
            e.target.value = '';
            return;
        }
        
        // Check file size
        if (file.size > maxSize) {
            alert(`Error: ${file.name} is too large. Maximum file size is 2MB.`);
            e.target.value = '';
            return;
        }
    }
});
</script>
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.products.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>