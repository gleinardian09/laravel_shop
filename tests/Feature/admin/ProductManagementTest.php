<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users and sample data
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'description' => 'Test product description',
        ]);
    }

    /** @test */
    public function admin_can_view_product_list()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.products.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_product()
    {
        // Use fake storage
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'New Product',
                'description' => 'This is a test product description',
                'price' => 199.99,
                'stock' => 10,
                'category_id' => $this->category->id,
                'is_active' => true,
                'image' => $file,
            ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'description' => 'This is a test product description',
        ]);

        // ✅ Check the fake file exists on the fake "public" disk
        Storage::disk('public')->assertExists('products/' . $file->hashName());
    }

    /** @test */
    public function admin_can_update_product()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $this->product), [
                'name' => 'Updated Product',
                'description' => 'Updated product description',
                'price' => 299.99,
                'stock' => 20,
                'category_id' => $this->category->id,
                'is_active' => false,
            ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Updated Product',
            'description' => 'Updated product description',
        ]);
    }

    /** @test */
    public function admin_can_delete_product()
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
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.products.index'));

        $response->assertStatus(403);
    }
}
