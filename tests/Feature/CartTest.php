<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $sessionId;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Generate a consistent session ID for all requests in this test class
        $this->sessionId = 'test-session-' . uniqid();
    }

    /** @test */
    public function cart_quantity_updates_when_adding_same_product()
    {
        $product = Product::factory()->create(['stock' => 10]);

        // Use the same session for both requests
        $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post("/cart/add/{$product->id}", [
                'quantity' => 2,
                '_token' => 'test-token'
            ]);
        
        // Second request with same session - should update existing item
        $response = $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post("/cart/add/{$product->id}", [
                'quantity' => 3,
                '_token' => 'test-token'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 5
        ]);
    }

    /** @test */
    public function user_can_view_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 29.99
        ]);

        // Add product to cart with specific session
        $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post("/cart/add/{$product->id}", [
                'quantity' => 1,
                '_token' => 'test-token'
            ]);

        // View cart with same session
        $response = $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->get('/cart');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('$' . number_format($product->price, 2));
    }

    /** @test */
    public function user_can_clear_entire_cart()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        // Add products to cart with same session
        $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post("/cart/add/{$product1->id}", [
                'quantity' => 1,
                '_token' => 'test-token'
            ]);
        $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post("/cart/add/{$product2->id}", [
                'quantity' => 2,
                '_token' => 'test-token'
            ]);

        // Verify items are in cart
        $this->assertDatabaseCount('cart_items', 2);

        // Clear cart with same session
        $response = $this->withSession(['_token' => 'test-token', '_previous' => ['url' => 'http://localhost']])
            ->post('/cart/clear', [
                '_token' => 'test-token'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Check that cart is empty
        $this->assertDatabaseCount('cart_items', 0);
    }

    // Keep your other test methods the same (they're working fine)
    /** @test */
    public function guest_can_add_product_to_cart()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->post("/cart/add/{$product->id}", [
            'quantity' => 2
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'user_id' => null
        ]);
    }

    /** @test */
    public function authenticated_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)
            ->post("/cart/add/{$product->id}", [
                'quantity' => 1
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 1
        ]);
    }

    /** @test */
    public function cannot_add_more_than_available_stock()
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->post("/cart/add/{$product->id}", [
            'quantity' => 10
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->id
        ]);
    }

    /** @test */
    public function user_can_update_cart_item_quantity()
    {
        $product = Product::factory()->create(['stock' => 10]);
        
        // Add product to cart
        $this->post("/cart/add/{$product->id}", ['quantity' => 1]);
        
        // Get the cart item that was created
        $cartItem = CartItem::where('product_id', $product->id)->first();

        $response = $this->put("/cart/update/{$cartItem->id}", [
            'quantity' => 3
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3
        ]);
    }

    /** @test */
    public function user_can_remove_item_from_cart()
    {
        $product = Product::factory()->create();
        
        // Add product to cart
        $this->post("/cart/add/{$product->id}", ['quantity' => 1]);
        
        // Get the cart item that was created
        $cartItem = CartItem::where('product_id', $product->id)->first();

        $response = $this->delete("/cart/remove/{$cartItem->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }
}