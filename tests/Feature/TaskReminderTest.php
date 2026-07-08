<?php

namespace Tests\Feature;

use App\Mail\TaskDueReminder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TaskReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_emails_users_about_pending_tasks_due_today_or_overdue(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $dueToday = Task::factory()->for($user)->create([
            'due_at' => Carbon::today(),
            'is_completed' => false,
        ]);

        $overdue = Task::factory()->for($user)->create([
            'due_at' => Carbon::yesterday(),
            'is_completed' => false,
        ]);

        Task::factory()->for($user)->create([
            'due_at' => Carbon::tomorrow(),
            'is_completed' => false,
        ]);

        Task::factory()->for($user)->create([
            'due_at' => Carbon::today(),
            'is_completed' => true,
        ]);

        $this->artisan('tasks:send-due-reminders')->assertSuccessful();

        Mail::assertSent(TaskDueReminder::class, 2);
        Mail::assertSent(TaskDueReminder::class, fn ($mail) => $mail->task->is($dueToday));
        Mail::assertSent(TaskDueReminder::class, fn ($mail) => $mail->task->is($overdue));
    }
}