<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks belonging ONLY to the logged-in user.
     */
    public function index()
    {
        // auth()->user()->tasks() uses our model relationship to pull only the current user's records!
        $userTasks = auth()->user()->tasks()->latest()->get();

        return view('tasks', [
            'tasks' => $userTasks
        ]);
    }

    /**
     * Store a newly created task linked to the current logged-in user.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Creates the task record through the logged-in user identity wrapper
        auth()->user()->tasks()->create([
            'title' => $validatedData['title'],
            'is_completed' => false,
        ]);

        return redirect('/tasks');
    }

    /**
     * Toggle status, adding an authorization block so users cannot change other people's tasks.
     */
    public function update(Task $task)
    {
        // Security Check: If the task does not belong to you, abort immediately with a 403 error
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect('/tasks');
    }

    /**
     * Permanently remove a task, protected by an identity check layer.
     */
    public function destroy(Task $task)
    {
        // Security Check: Abort if someone alters form values to delete someone else's data rows
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect('/tasks');
    }
}
