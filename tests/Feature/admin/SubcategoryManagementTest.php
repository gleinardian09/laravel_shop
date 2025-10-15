<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubcategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function non_admin_cannot_view_subcategories_index()
    {
        $this->actingAs($this->user)
             ->get(route('admin.subcategories.index'))
             ->assertForbidden();
    }

    /** @test */
    public function admin_can_create_subcategory()
    {
        $data = [
            'name' => 'New Subcategory',
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->admin)
             ->post(route('admin.subcategories.store'), $data)
             ->assertRedirect(route('admin.subcategories.index'));

        $this->assertDatabaseHas('subcategories', [
            'name' => 'New Subcategory',
            'slug' => 'new-subcategory',
        ]);
    }

    /** @test */
    public function admin_can_update_subcategory()
    {
        $subcategory = Subcategory::factory()->create(['category_id' => $this->category->id]);

        $updateData = [
            'name' => 'Updated Subcategory',
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->admin)
             ->put(route('admin.subcategories.update', $subcategory->id), $updateData)
             ->assertRedirect(route('admin.subcategories.index'));

        $this->assertDatabaseHas('subcategories', [
            'id' => $subcategory->id,
            'name' => 'Updated Subcategory',
        ]);
    }

    /** @test */
    public function admin_can_delete_subcategory()
    {
        $subcategory = Subcategory::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($this->admin)
             ->delete(route('admin.subcategories.destroy', $subcategory->id))
             ->assertRedirect(route('admin.subcategories.index'));

        $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id]);
    }
}
