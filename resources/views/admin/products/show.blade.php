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
                        <!-- Product Images with Navigation -->
                        <div>
                            <div class="relative">
                                @if($product->images->count() > 0)
                                    <!-- Main Image Display - Clickable for Lightbox -->
                                    <div class="mb-4 relative">
                                        <img id="mainProductImage" 
                                             src="{{ asset('storage/' . $product->images->firstWhere('is_primary', true)?->image_path ?? $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-96 object-cover rounded-lg shadow-lg transition-opacity duration-300 cursor-zoom-in"
                                             onclick="openLightbox(currentIndex)">
                                        
                                        <!-- Navigation Buttons -->
                                        @if($product->images->count() > 1)
                                            <button id="prevImage" 
                                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-70 transition-all duration-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                            </button>
                                            <button id="nextImage" 
                                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-70 transition-all duration-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Image Thumbnails -->
                                    <div class="flex space-x-2 overflow-x-auto pb-2">
                                        @foreach($product->images as $index => $image)
                                            <button class="thumbnail-btn flex-shrink-0 w-20 h-20 border-2 rounded-lg overflow-hidden transition-all duration-200 
                                                          {{ ($image->is_primary || ($index === 0 && !$product->images->where('is_primary', true)->first())) ? 'border-blue-500' : 'border-gray-300' }}"
                                                    data-image-src="{{ asset('storage/' . $image->image_path) }}"
                                                    data-image-index="{{ $index }}"
                                                    onclick="openLightbox({{ $index }})">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                                     class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Image Counter -->
                                    <div class="text-center mt-2 text-sm text-gray-600">
                                        <span id="currentImageIndex">1</span> of {{ $product->images->count() }}
                                    </div>
                                @else
                                    <!-- Fallback to single image if no multiple images -->
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-96 object-cover rounded-lg shadow-lg cursor-zoom-in"
                                             onclick="openLightbox(0)">
                                    @else
                                        <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-lg">No image available</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
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

                            <!-- Updated Category Section -->
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
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full w-full">
            <!-- Close Button -->
            <button onclick="closeLightbox()" 
                    class="absolute top-4 right-4 text-white text-3xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all duration-200">
                ✕
            </button>

            <!-- Lightbox Navigation Buttons -->
            @if($product->images->count() > 1)
                <button id="lightboxPrev" 
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-4 rounded-full hover:bg-opacity-70 transition-all duration-200 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="lightboxNext" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-4 rounded-full hover:bg-opacity-70 transition-all duration-200 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif

            <!-- Lightbox Image -->
            <img id="lightboxImage" 
                 class="w-full h-auto max-h-screen object-contain rounded-lg"
                 alt="{{ $product->name }}">

            <!-- Lightbox Image Counter -->
            @if($product->images->count() > 1)
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                    <span id="lightboxImageIndex">1</span> of {{ $product->images->count() }}
                </div>
            @endif

            <!-- Lightbox Thumbnails -->
            @if($product->images->count() > 1)
                <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 flex space-x-2 overflow-x-auto max-w-full">
                    @foreach($product->images as $index => $image)
                        <button class="lightbox-thumbnail flex-shrink-0 w-16 h-16 border-2 rounded-lg overflow-hidden transition-all duration-200 
                                      {{ ($image->is_primary || ($index === 0 && !$product->images->where('is_primary', true)->first())) ? 'border-white' : 'border-gray-400' }}"
                                onclick="showLightboxImage({{ $index }})">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        let currentIndex = 0;
        const images = @json($product->images->pluck('image_path'));
        const lightboxModal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxImageIndex = document.getElementById('lightboxImageIndex');
        const lightboxThumbnails = document.querySelectorAll('.lightbox-thumbnail');

        // Function to update main image
        function updateMainImage(index) {
            if (images[index]) {
                const mainImage = document.getElementById('mainProductImage');
                mainImage.src = "{{ asset('storage/') }}/" + images[index];
                currentIndex = index;
                document.getElementById('currentImageIndex').textContent = index + 1;
                
                // Update thumbnail borders
                document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
                    btn.classList.toggle('border-blue-500', i === index);
                    btn.classList.toggle('border-gray-300', i !== index);
                });
            }
        }

        // Open lightbox
        function openLightbox(index) {
            currentIndex = index;
            showLightboxImage(index);
            lightboxModal.classList.remove('hidden');
            lightboxModal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        // Close lightbox
        function closeLightbox() {
            lightboxModal.classList.add('hidden');
            lightboxModal.classList.remove('flex');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Show specific image in lightbox
        function showLightboxImage(index) {
            if (images[index]) {
                currentIndex = index;
                lightboxImage.src = "{{ asset('storage/') }}/" + images[index];
                lightboxImageIndex.textContent = index + 1;
                
                // Update lightbox thumbnail borders
                lightboxThumbnails.forEach((thumb, i) => {
                    thumb.classList.toggle('border-white', i === index);
                    thumb.classList.toggle('border-gray-400', i !== index);
                });
            }
        }

        // Lightbox navigation
        document.getElementById('lightboxPrev')?.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showLightboxImage(currentIndex);
        });

        document.getElementById('lightboxNext')?.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % images.length;
            showLightboxImage(currentIndex);
        });

        // Close lightbox on background click
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === lightboxModal) {
                closeLightbox();
            }
        });

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', function(e) {
            if (!lightboxModal.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    showLightboxImage(currentIndex);
                } else if (e.key === 'ArrowRight') {
                    currentIndex = (currentIndex + 1) % images.length;
                    showLightboxImage(currentIndex);
                }
            }
        });

        // Original image navigation (for main view)
        document.addEventListener('DOMContentLoaded', function() {
            const prevButton = document.getElementById('prevImage');
            const nextButton = document.getElementById('nextImage');
            const thumbnailButtons = document.querySelectorAll('.thumbnail-btn');

            if (prevButton) {
                prevButton.addEventListener('click', function() {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    updateMainImage(currentIndex);
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function() {
                    currentIndex = (currentIndex + 1) % images.length;
                    updateMainImage(currentIndex);
                });
            }

            thumbnailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-image-index'));
                    updateMainImage(index);
                });
            });

            // Keyboard navigation for main view
            document.addEventListener('keydown', function(e) {
                if (images.length <= 1 || !lightboxModal.classList.contains('hidden')) return;
                
                if (e.key === 'ArrowLeft') {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    updateMainImage(currentIndex);
                } else if (e.key === 'ArrowRight') {
                    currentIndex = (currentIndex + 1) % images.length;
                    updateMainImage(currentIndex);
                }
            });
        });
    </script>
</x-app-layout>