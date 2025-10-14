<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    /** @test */
    public function product_has_formatted_price()
    {
        $product = Product::factory()->create(['price' => 29.99]);

        $this->assertEquals('$29.99', $product->formatted_price);
    }

    /** @test */
    public function product_can_have_reviews()
    {
        $product = Product::factory()->create();
        $review = Review::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Review::class, $product->reviews->first());
        $this->assertEquals($review->id, $product->reviews->first()->id);
    }

    /** @test */
    public function product_calculates_average_rating()
    {
        $product = Product::factory()->create();
        
        Review::factory()->create([
            'product_id' => $product->id,
            'rating' => 4,
            'is_approved' => true
        ]);
        Review::factory()->create([
            'product_id' => $product->id,
            'rating' => 5,
            'is_approved' => true
        ]);

        $this->assertEquals(4.5, $product->average_rating);
    }

    /** @test */
    public function product_search_scope_works()
    {
        $product1 = Product::factory()->create(['name' => 'Test Product']);
        $product2 = Product::factory()->create(['name' => 'Another Item']);

        $results = Product::search('Test')->get();

        $this->assertTrue($results->contains($product1));
        $this->assertFalse($results->contains($product2));
    }
}