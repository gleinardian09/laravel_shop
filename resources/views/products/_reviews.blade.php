<div class="mt-8 border-t pt-8">
    <h3 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h3>

    <!-- Rating Summary -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <div class="text-4xl font-bold text-gray-900">{{ number_format($product->average_rating, 1) }}/5</div>
                <div class="flex justify-center md:justify-start text-yellow-400 text-xl mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->average_rating))
                            ★
                        @elseif($i - 0.5 <= $product->average_rating)
                            ⭐
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <div class="text-gray-600 mt-2">Based on {{ $product->rating_count }} reviews</div>
            </div>

            <!-- Rating Distribution -->
            <div class="flex-1 max-w-md">
                @for($rating = 5; $rating >= 1; $rating--)
                    @php
                        $count = $product->approvedReviews->where('rating', $rating)->count();
                        $percentage = $product->rating_count > 0 ? ($count / $product->rating_count) * 100 : 0;
                    @endphp
                    <div class="flex items-center mb-2">
                        <span class="text-sm text-gray-600 w-8">{{ $rating }}★</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Review Form (if user can review) -->
    @auth
        @if(!$product->user_review && auth()->user()->orders()->whereHas('items', function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->where('status', 'completed')->exists())
            @include('products._review_form')
        @endif
    @endauth

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse($product->approvedReviews as $review)
            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                        <div class="flex text-yellow-400 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $review->created_at->format('M j, Y') }}</span>
                </div>

                @if($review->comment)
                    <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                @else
                    <p class="text-gray-500 italic mt-2">No comment provided</p>
                @endif

                <!-- Review Actions (if user's own review) -->
                @auth
                    @if($review->user_id === auth()->id())
                        <div class="mt-3 flex space-x-2">
                            <button onclick="editReview({{ $review->id }})" 
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </button>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete your review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="text-center py-8">
                <div class="text-4xl mb-4">💬</div>
                <h4 class="text-xl font-semibold text-gray-900 mb-2">No Reviews Yet</h4>
                <p class="text-gray-600">Be the first to review this product!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function editReview(reviewId) {
    // This would open a modal or form to edit the review
    // For now, we'll just show an alert
    alert('Edit functionality would open here for review ID: ' + reviewId);
}
</script>