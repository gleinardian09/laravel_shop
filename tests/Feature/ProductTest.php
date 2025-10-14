<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_view_products_index()
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->get('/products');
        
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function guests_can_view_individual_product()
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->get("/products/{$product->slug}");
        
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->description);
    }

    /** @test */
    public function inactive_products_return_404()
    {
        $product = Product::factory()->create(['is_active' => false]);

        $response = $this->get("/products/{$product->slug}");
        
        $response->assertStatus(404);
    }

    /** @test */
    public function products_can_be_filtered_by_category()
    {
        $category = Category::factory()->create();
        $productInCategory = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true
        ]);
        $productNotInCategory = Product::factory()->create(['is_active' => true]);

        $response = $this->get("/products?category={$category->slug}");
        
        $response->assertSee($productInCategory->name);
        $response->assertDontSee($productNotInCategory->name);
    }

    /** @test */
    public function related_products_are_displayed()
    {
        $category = Category::factory()->create();
        $mainProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true
        ]);
        $relatedProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true
        ]);

        $response = $this->get("/products/{$mainProduct->slug}");
        
        $response->assertSee($relatedProduct->name);
    }
}