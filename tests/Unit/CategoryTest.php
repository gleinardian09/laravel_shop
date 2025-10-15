<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Subcategory;


class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_slug_from_name()
    {
        $category = Category::factory()->named('Test Category Name')->create();

        $this->assertEquals('test-category-name', $category->slug);
    }

    /** @test */
    public function it_can_be_active_or_inactive()
    {
        $category = Category::factory()->create(['is_active' => false]);
        $this->assertFalse($category->is_active);
    }

    /** @test */
    public function it_has_many_subcategories()
    {
        $category = Category::factory()->create();
        $subcategory = $category->subcategories()->create(
            Subcategory::factory()->make()->toArray()
        );

        $this->assertTrue($category->subcategories->contains($subcategory));
    }

    /** @test */
    public function it_has_many_products()
    {
        $category = Category::factory()->create();
        $product = $category->products()->create(
            \App\Models\Product::factory()->make()->toArray()
        );

        $this->assertTrue($category->products->contains($product));
    }
}
