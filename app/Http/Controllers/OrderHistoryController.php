<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        return view('orders.show', compact('order'));
    }

    public function reorder($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        \Log::info('=== REORDER DEBUG START ===');
        \Log::info('Order details:', [
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'items_count' => $order->items->count()
        ]);

        $addedItemsCount = 0;
        $unavailableItems = [];
        $outOfStockItems = [];

        DB::transaction(function () use ($order, &$addedItemsCount, &$unavailableItems, &$outOfStockItems) {
            foreach ($order->items as $index => $orderItem) {
                \Log::info("Processing item {$index}:", [
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'product_name' => $orderItem->product_name ?? 'N/A',
                    'quantity' => $orderItem->quantity
                ]);

                // Check if product still exists
                $product = Product::find($orderItem->product_id);
                
                if (!$product) {
                    \Log::warning("Product not found in database", [
                        'product_id' => $orderItem->product_id
                    ]);
                    $unavailableItems[] = "{$orderItem->product_name} (No longer available)";
                    continue;
                }

                \Log::info("Product found:", [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'is_available' => $product->is_available,
                    'stock_quantity' => $product->stock_quantity,
                    'price' => $product->price
                ]);

                // Check if product is available
                if (!$product->is_available) {
                    \Log::warning("Product is not available", [
                        'product_id' => $product->id,
                        'is_available' => $product->is_available
                    ]);
                    $unavailableItems[] = $product->name;
                    continue;
                }

                // Check if product is in stock
                if ($product->stock_quantity < $orderItem->quantity) {
                    \Log::warning("Insufficient stock", [
                        'product_id' => $product->id,
                        'required' => $orderItem->quantity,
                        'available' => $product->stock_quantity
                    ]);
                    $outOfStockItems[] = "{$product->name} (Only {$product->stock_quantity} in stock)";
                    continue;
                }

                // Add to cart logic
                $existingCartItem = CartItem::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingCartItem) {
                    $newQuantity = $existingCartItem->quantity + $orderItem->quantity;
                    $existingCartItem->update([
                        'quantity' => $newQuantity,
                        'price' => $product->price
                    ]);
                    \Log::info("Updated existing cart item", [
                        'cart_item_id' => $existingCartItem->id,
                        'new_quantity' => $newQuantity
                    ]);
                } else {
                    $cartItem = CartItem::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'quantity' => $orderItem->quantity,
                        'price' => $product->price
                    ]);
                    \Log::info("Created new cart item", [
                        'cart_item_id' => $cartItem->id
                    ]);
                }

                $addedItemsCount++;
            }
        });

        \Log::info('=== REORDER DEBUG END ===', [
            'added_items_count' => $addedItemsCount,
            'unavailable_items' => $unavailableItems,
            'out_of_stock_items' => $outOfStockItems
        ]);

        // Prepare success message
        $message = "";
        
        if ($addedItemsCount > 0) {
            $message = "✅ {$addedItemsCount} item(s) from your order have been added to cart!";
        } else {
            $message = "No items could be added to your cart.";
        }

        // Add warnings for unavailable items
        if (!empty($unavailableItems)) {
            $message .= "\n❌ Unavailable: " . implode(', ', array_unique($unavailableItems));
        }

        // Add warnings for out of stock items
        if (!empty($outOfStockItems)) {
            $message .= "\n⚠️ Out of stock: " . implode(', ', array_unique($outOfStockItems));
        }

        return redirect()->route('cart.index')
            ->with('success', $message);
    }
}