<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_create_categories(): void
    {
        $this->post('/categories', ['name' => 'Work'])->assertRedirect('/login');
    }

    public function test_user_can_create_a_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/categories', ['name' => 'Work'])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Work',
        ]);
    }

    public function test_category_creation_requires_a_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/categories', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_user_can_rename_their_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create(['name' => 'Old Name']);

        $this->actingAs($user)
            ->put(route('categories.update', $category), ['name' => 'New Name'])
            ->assertRedirect(route('tasks.index'));

        $this->assertSame('New Name', $category->fresh()->name);
    }

    public function test_user_cannot_rename_another_users_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->for($owner)->create(['name' => 'Old Name']);

        $this->actingAs($intruder)
            ->put(route('categories.update', $category), ['name' => 'Hijacked'])
            ->assertForbidden();

        $this->assertSame('Old Name', $category->fresh()->name);
    }

    public function test_user_can_delete_their_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_another_users_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}