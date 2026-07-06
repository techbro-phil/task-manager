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

    public function test_user_can_toggle_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['is_completed' => false]);

        $this->actingAs($user)
            ->put("/tasks/{$task->id}")
            ->assertRedirect(route('tasks.index'));

        $this->assertTrue($task->fresh()->is_completed);
    }

    public function test_user_cannot_toggle_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->put("/tasks/{$task->id}")
            ->assertForbidden();

        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->delete("/tasks/{$task->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}