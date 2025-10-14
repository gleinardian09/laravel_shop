<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->category = Category::factory()->create();
        Storage::fake('public');
    }

    /** @test */
    public function admin_can_view_product_list()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.products.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_a_product()
    {
        $file = UploadedFile::fake()->image('sample.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $this->category->id,
            'is_active' => true,
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertTrue(Storage::disk('public')->exists('products/' . $file->hashName()));
    }

    /** @test */
    public function admin_can_update_a_product()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Product',
            'price' => 299.99,
            'stock' => 5,
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    /** @test */
    public function admin_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function non_admin_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get(route('admin.products.index'));

        $response->assertStatus(403);
    }
}
