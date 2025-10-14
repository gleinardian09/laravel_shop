<div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
    <h4 class="text-lg font-semibold text-gray-900 mb-4">Write a Review</h4>
    
    <form action="{{ route('reviews.store', $product) }}" method="POST">
        @csrf
        
        <!-- Rating -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
            <div class="flex space-x-1" id="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" 
                            class="text-2xl rating-star {{ $i <= 3 ? 'text-yellow-400' : 'text-gray-300' }}" 
                            data-rating="{{ $i }}"
                            onclick="setRating({{ $i }})">
                        ★
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="3" required>
        </div>

        <!-- Comment -->
        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
            <textarea name="comment" id="comment" rows="4" 
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Share your experience with this product..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
            Submit Review
        </button>
    </form>
</div>

<script>
let currentRating = 3;

function setRating(rating) {
    currentRating = rating;
    document.getElementById('rating-input').value = rating;
    
    // Update star display
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}
</script>