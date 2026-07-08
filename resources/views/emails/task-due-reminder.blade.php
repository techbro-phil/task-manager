<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #26241E; background: #FBF8F2; padding: 24px;">
    <h2 style="margin-bottom: 4px;">Task Due Reminder</h2>
    <p>Hi {{ $task->user->name }},</p>
    <p>Your task <strong>"{{ $task->title }}"</strong> is due on <strong>{{ $task->due_at->format('l, F j, Y') }}</strong>.</p>
    <p>
        <a href="{{ route('tasks.index') }}" style="display:inline-block; background:#B8813A; color:#fff; padding:10px 18px; border-radius:4px; text-decoration:none;">
            View My Tasks
        </a>
    </p>
    <p style="color:#6B6558; font-size:12px;">— {{ config('app.name') }}</p>
</body>
</html>