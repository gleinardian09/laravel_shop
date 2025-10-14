<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Welcome to ShopApp');
    }

    /** @test */
    public function home_page_displays_featured_products()
    {
        $featuredProduct = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertSee($featuredProduct->name);
        $response->assertSee($featuredProduct->formatted_price);
    }

    /** @test */
    public function home_page_displays_categories()
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee($category->name);
    }

    /** @test */
    public function home_page_handles_database_errors_gracefully()
    {
        \DB::shouldReceive('table')->andThrow(new \Exception('DB error'));

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
