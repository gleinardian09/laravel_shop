<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function it_generates_slug_from_name()
    {
        $subcategory = Subcategory::factory()
            ->named('Test Subcategory Name')
            ->create(['category_id' => $this->category->id]);

        $this->assertEquals('test-subcategory-name', $subcategory->slug);
    }

    /** @test */
    public function it_can_be_active_or_inactive()
    {
        $subcategory = Subcategory::factory()->create(['is_active' => false]);
        $this->assertFalse($subcategory->is_active);
    }

    /** @test */
    public function it_belongs_to_category()
    {
        $subcategory = Subcategory::factory()->create(['category_id' => $this->category->id]);
        $this->assertEquals($this->category->id, $subcategory->category->id);
    }
}
