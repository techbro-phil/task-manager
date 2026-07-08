<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userCategories = $request->user()->categories()->latest()->get();

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        if (! in_array($sort, ['created_at', 'due_at', 'title'], true)) {
            $sort = 'created_at';
        }

        $userTasks = $request->user()->tasks()
            ->with('category')
            ->when($request->filled('category_id'), fn ($query) =>
                $query->where('category_id', $request->category_id)
            )
            ->when($request->filled('search'), fn ($query) =>
                $query->where('title', 'like', '%' . $request->string('search') . '%')
            )
            ->orderBy($sort, $direction)
            ->get();

        return view('tasks', [
            'tasks' => $userTasks,
            'categories' => $userCategories,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create([
            'title' => $request->validated('title'),
            'category_id' => $request->validated('category_id'),
            'due_at' => $request->validated('due_at'),
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task added.');
    }

    /**
     * Edit a task's title, due date, or category.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update([
            'title' => $request->validated('title'),
            'category_id' => $request->validated('category_id'),
            'due_at' => $request->validated('due_at'),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    /**
     * Flip a task between pending and done.
     */
    public function toggle(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update(['is_completed' => ! $task->is_completed]);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}