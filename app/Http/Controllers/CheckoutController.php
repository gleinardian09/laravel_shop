<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $this->getCartTotal($cartItems);
        
        return view('checkout.show', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->stock) {
                return back()->with('error', "Sorry, {$cartItem->product->name} only has {$cartItem->product->stock} items in stock.");
            }
        }

        DB::transaction(function () use ($request, $cartItems) {
            $total = $this->getCartTotal($cartItems);
            
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Create order items and update product stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'unit_price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->quantity * $cartItem->product->price,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            $this->clearCart();
        });

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('home');
        }

        return view('checkout.success');
    }

    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::with('product')->where('user_id', auth()->id())->get();
        } else {
            return CartItem::with('product')->where('session_id', session()->getId())->get();
        }
    }

    private function getCartTotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    private function clearCart()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }
    }
}