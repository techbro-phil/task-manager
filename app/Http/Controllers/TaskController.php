<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks pulled straight from the database.
     */
    public function index()
    {
        return view('tasks', [
            'tasks' => Task::latest()->get()
        ]);
    }

    /**
     * Store a newly created task in the database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create([
            'title' => $validatedData['title'],
            'is_completed' => false,
        ]);

        return redirect('/tasks');
    }

    /**
     * Toggle the completion status of a specific task.
     */
    public function update(Task $task)
    {
        // Flip the current boolean value (true becomes false, false becomes true)
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect('/tasks');
    }

    /**
     * Permanently remove a specific task from the database.
     */
    public function destroy(Task $task)
    {
        // Eloquent deletes this specific row match instantly
        $task->delete();

        return redirect('/tasks');
    }
}
