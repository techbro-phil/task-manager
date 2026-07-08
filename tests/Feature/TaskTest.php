<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/tasks')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_their_task_list(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->get('/tasks')
            ->assertOk()
            ->assertSee($task->title);
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/tasks', ['title' => 'Write the project README'])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Write the project README',
        ]);
    }

    public function test_task_creation_requires_a_title(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/tasks', ['title' => ''])
            ->assertSessionHasErrors('title');
    }

    public function test_user_can_edit_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['title' => 'Old title']);

        $this->actingAs($user)
            ->put(route('tasks.update', $task), ['title' => 'New title'])
            ->assertRedirect(route('tasks.index'));

        $this->assertSame('New title', $task->fresh()->title);
    }

    public function test_user_cannot_edit_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create(['title' => 'Old title']);

        $this->actingAs($intruder)
            ->put(route('tasks.update', $task), ['title' => 'Hijacked'])
            ->assertForbidden();

        $this->assertSame('Old title', $task->fresh()->title);
    }

    public function test_user_can_toggle_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['is_completed' => false]);

        $this->actingAs($user)
            ->patch(route('tasks.toggle', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertTrue($task->fresh()->is_completed);
    }

    public function test_user_cannot_toggle_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->patch(route('tasks.toggle', $task))
            ->assertForbidden();

        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_task_list_can_be_searched_by_title(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create(['title' => 'Buy groceries']);
        Task::factory()->for($user)->create(['title' => 'Write report']);

        $response = $this->actingAs($user)->get('/tasks?search=groceries');

        $response->assertSee('Buy groceries');
        $response->assertDontSee('Write report');
    }
}