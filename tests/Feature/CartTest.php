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

    protected string $sessionKey = 'test-session-123';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function guest_can_add_product_to_cart()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 2]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'user_id' => null,
        ]);
    }

    /** @test */
    public function authenticated_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)
            ->post("/cart/add/{$product->id}", ['quantity' => 1]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function cannot_add_more_than_available_stock()
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 10]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
    }

    /** @test */
    public function cart_quantity_updates_when_adding_same_product()
    {
        $product = Product::factory()->create(['stock' => 10]);

        // First add
        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 2]);

        // Add same product again
        $response = $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 3]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    /** @test */
    public function user_can_update_cart_item_quantity()
    {
        $product = Product::factory()->create(['stock' => 10]);

        // Add to cart first
        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 1]);

        $cartItem = CartItem::first();

        $response = $this->put("/cart/update/{$cartItem->id}", [
            'quantity' => 3,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    /** @test */
    public function user_can_remove_item_from_cart()
    {
        $product = Product::factory()->create();

        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 1]);

        $cartItem = CartItem::first();

        $response = $this->delete("/cart/remove/{$cartItem->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    /** @test */
    public function user_can_view_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 29.99,
        ]);

        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product->id}", ['quantity' => 1]);

        $response = $this->withSession(['cart_session' => $this->sessionKey])
            ->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('29.99');
    }

    /** @test */
    public function user_can_clear_entire_cart()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product1->id}", ['quantity' => 1]);

        $this->withSession(['cart_session' => $this->sessionKey])
            ->post("/cart/add/{$product2->id}", ['quantity' => 2]);

        $this->assertDatabaseCount('cart_items', 2);

        $response = $this->withSession(['cart_session' => $this->sessionKey])
            ->post('/cart/clear');

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('cart_items', 0);
    }
}
