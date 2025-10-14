<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response = $this->actingAs($user)->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee('Checkout');
        $response->assertSee($product->name);
    }

    /** @test */
    public function guest_cannot_access_checkout()
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_with_empty_cart_cannot_access_checkout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/checkout');

        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }

    /** @test */
    public function user_can_complete_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 25.00]);
        
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_address' => '123 Test St, Test City',
            'billing_address' => '123 Test St, Test City',
            'customer_phone' => '123-456-7890',
            'notes' => 'Test order notes'
        ]);

        $response->assertRedirect('/checkout/success');
        $response->assertSessionHas('success');
        
        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 50.00,
            'status' => 'pending'
        ]);
        
        // Verify stock was updated
        $this->assertEquals(8, $product->fresh()->stock);
        
        // Verify cart was cleared
        $this->assertDatabaseCount('cart_items', 0);
    }

    /** @test */
    public function checkout_fails_when_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);
        
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 5
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_address' => '123 Test St',
            'billing_address' => '123 Test St',
            'customer_phone' => '123-456-7890'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        // Verify no order was created
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function checkout_requires_valid_data()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user)->post('/checkout', []);

        $response->assertSessionHasErrors([
            'shipping_address', 'billing_address', 'customer_phone'
        ]);
    }
}