<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the authenticated user's tasks and categories,
     * with optional filtering by category.
     */
    public function index(Request $request)
    {
        $userCategories = $request->user()->categories()->latest()->get();

        $userTasks = $request->user()->tasks()
            ->with('category')
            ->when($request->filled('category_id'), fn ($query) =>
                $query->where('category_id', $request->category_id)
            )
            ->latest()
            ->get();

        return view('tasks', [
            'tasks' => $userTasks,
            'categories' => $userCategories,
        ]);
    }

    /**
     * Store a newly created task linked to the authenticated user.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create([
            'title' => $request->validated('title'),
            'category_id' => $request->validated('category_id'),
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task added.');
    }

    /**
     * Toggle a task's completion status. Only the owner may do this.
     */
    public function update(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    /**
     * Permanently remove a task. Only the owner may do this.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}