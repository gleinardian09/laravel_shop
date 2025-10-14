<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $this->getCartTotal($cartItems);

        \Log::info('Cart Index - Final items:', [
            'count' => $cartItems->count(),
            'items' => $cartItems->pluck('product.name')->toArray(),
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
        ]);

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add($productId, Request $request)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity ?? 1;

        try {
            DB::transaction(function () use ($product, $quantity) {
                $cartItem = $this->getCartItemByProduct($product->id);

                \Log::info('Cart Add Transaction', [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'existing_item' => $cartItem ? $cartItem->id : 'none',
                    'session_id' => session()->getId(),
                    'user_id' => auth()->id(),
                ]);

                $totalQuantity = $cartItem ? ($cartItem->quantity + $quantity) : $quantity;

                if ($totalQuantity > $product->stock) {
                    throw new \Exception('Cannot add more than available stock.');
                }

                if ($cartItem) {
                    // Update existing cart item
                    $cartItem->update(['quantity' => $totalQuantity]);
                    \Log::info("Updated cart item {$cartItem->id} to quantity {$totalQuantity}");
                } else {
                    // Create new cart item
                    $cartItemData = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'user_id' => auth()->id(),
                        'session_id' => $this->getStableSessionId(),
                    ];

                    $newItem = CartItem::create($cartItemData);
                    \Log::info("Created new cart item: {$newItem->id}");
                }
            });

            return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(CartItem $cartItem, Request $request)
    {
        $cartItem->load('product.category');

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        $cartItems = $this->getCartItems();

        \Log::info('Clearing cart - Final check:', [
            'items_count' => $cartItems->count(),
            'item_ids' => $cartItems->pluck('id'),
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
        ]);

        $deletedCount = $cartItems->each->delete()->count();

        \Log::info("Deleted {$deletedCount} cart items");

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    /**
     * Retrieve or create a stable session ID for guest carts.
     * This prevents duplicate cart items across multiple requests/tests.
     */
    private function getStableSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => session()->getId() ?? session()->get('_token')]);
        }

        return session('cart_session_id');
    }

    /**
     * Retrieve an existing cart item by product for current user/session.
     */
    private function getCartItemByProduct($productId)
    {
        $query = CartItem::where('product_id', $productId);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $this->getStableSessionId());
        }

        return $query->first();
    }

    /**
     * Calculate total price of all items in the cart.
     */
    private function getCartTotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Retrieve all cart items for the current user/session.
     */
    private function getCartItems()
    {
        if (auth()->check()) {
            $items = CartItem::with(['product.category'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $items = CartItem::with(['product.category'])
                ->where('session_id', $this->getStableSessionId())
                ->get();
        }

        \Log::info('Final Cart Items Retrieved', [
            'auth' => auth()->check(),
            'session_id' => $this->getStableSessionId(),
            'user_id' => auth()->id(),
            'count' => $items->count(),
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                ];
            })->toArray(),
        ]);

        return $items;
    }
}
