<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /** @test */
    public function order_has_many_items()
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(OrderItem::class, $order->items->first());
        $this->assertEquals($item->id, $order->items->first()->id);
    }

    /** @test */
    public function order_generates_unique_order_number()
    {
        $orderNumber1 = Order::generateOrderNumber();
        $orderNumber2 = Order::generateOrderNumber();

        $this->assertStringStartsWith('ORD-', $orderNumber1);
        $this->assertNotEquals($orderNumber1, $orderNumber2);
    }

    /** @test */
    public function order_has_status_color()
    {
        $order = Order::factory()->create(['status' => 'pending']);
        
        $this->assertEquals('yellow', $order->status_color);
        
        $order->status = 'completed';
        $this->assertEquals('green', $order->status_color);
    }
}