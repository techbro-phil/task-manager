<?php

namespace App\Console\Commands;

use App\Mail\TaskDueReminder;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTaskDueReminders extends Command
{
    protected $signature = 'tasks:send-due-reminders';

    protected $description = 'Email users about pending tasks due today or overdue.';

    public function handle(): int
    {
        $tasks = Task::query()
            ->with('user')
            ->whereNotNull('due_at')
            ->where('is_completed', false)
            ->whereDate('due_at', '<=', Carbon::today())
            ->get();

        foreach ($tasks as $task) {
            Mail::to($task->user->email)->send(new TaskDueReminder($task));
        }

        $this->info("Sent {$tasks->count()} reminder email(s).");

        return self::SUCCESS;
    }
}