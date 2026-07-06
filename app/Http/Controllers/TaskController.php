<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks and categories belonging ONLY to the logged-in user.
     */
    public function index()
    {
        $userTasks = auth()->user()->tasks()->with('category')->latest()->get();
        $userCategories = auth()->user()->categories()->latest()->get();

        return view('tasks', [
            'tasks' => $userTasks,
            'categories' => $userCategories
        ]);
    }

    /**
     * Store a newly created task linked to a user and optional category.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id', 
        ]);

        auth()->user()->tasks()->create([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'is_completed' => false,
        ]);

        return redirect('/tasks');
    }

    /**
     * Toggle status, protecting with an authorization block.
     */
    public function update(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect('/tasks');
    }

    /**
     * Permanently remove a task row.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect('/tasks');
    }
}
