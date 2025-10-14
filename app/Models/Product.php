<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 
        'stock', 'image', 'category_id', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Add multiple images relationship
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // Get main image - checks multiple images first, then falls back to single image
    public function getMainImageAttribute()
    {
        if ($this->images->count() > 0) {
            $primary = $this->images->where('is_primary', true)->first();
            return $primary ? $primary->image_path : $this->images->first()->image_path;
        }
        return $this->image; // Fallback to original single image
    }

    // Get all images including the main one
    public function getAllImagesAttribute()
    {
        $allImages = collect();
        
        // Add multiple images
        if ($this->images->count() > 0) {
            $allImages = $this->images->pluck('image_path');
        }
        // Add original single image if it exists and not already included
        elseif ($this->image) {
            $allImages->push($this->image);
        }
        
        return $allImages;
    }

    // Keep your existing methods...
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format((float) $this->price, 2);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getRatingCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingPercentageAttribute()
    {
        if ($this->rating_count === 0) return 0;
        return ($this->average_rating / 5) * 100;
    }

    public function getUserReviewAttribute()
    {
        if (!auth()->check()) return null;
        
        return $this->reviews()
            ->where('user_id', auth()->id())
            ->first();
    }

    // Search scope
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeFilterByPrice($query, $minPrice, $maxPrice)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeSortBy($query, $sort)
    {
        switch ($sort) {
            case 'price_low':
                return $query->orderBy('price', 'asc');
            case 'price_high':
                return $query->orderBy('price', 'desc');
            case 'name':
                return $query->orderBy('name', 'asc');
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'rating':
                return $query->withAvg('approvedReviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
    // Add this method to your Product model
public function getHighQualityImageUrl($imagePath = null)
{
    if (!$imagePath) {
        if ($this->images->count() > 0) {
            $primaryImage = $this->images->where('is_primary', true)->first();
            $imagePath = $primaryImage ? $primaryImage->image_path : $this->images->first()->image_path;
        } else {
            $imagePath = $this->image;
        }
    }
    
    return $imagePath ? asset('storage/' . $imagePath) . '?t=' . time() : null;
}
// Automatically generate slug from name if not provided
protected static function boot()
{
    parent::boot();

    static::creating(function ($product) {
        if (empty($product->slug)) {
            $product->slug = \Illuminate\Support\Str::slug($product->name);
        }
    });

    static::updating(function ($product) {
        // Optional: update slug if name changes
        if ($product->isDirty('name') && empty($product->slug)) {
            $product->slug = \Illuminate\Support\Str::slug($product->name);
        }
    });
}

}