<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'user_id', 'product_id', 'quantity'];
    // Remove 'price' from fillable since the column doesn't exist

    public static function boot()
    {
        parent::boot();

        static::creating(function ($cartItem) {
            // Check for existing cart item with same product and user/session
            $existing = static::where('product_id', $cartItem->product_id)
                ->where(function($query) use ($cartItem) {
                    if ($cartItem->user_id) {
                        $query->where('user_id', $cartItem->user_id);
                    } else {
                        $query->where('session_id', $cartItem->session_id);
                    }
                })
                ->first();

            if ($existing) {
                // Instead of creating new, update existing
                $existing->update([
                    'quantity' => $existing->quantity + $cartItem->quantity
                ]);
                return false; // Prevent creating new item
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }
}