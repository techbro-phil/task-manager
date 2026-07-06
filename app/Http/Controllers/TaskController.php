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
        // Pull tasks sorted by latest first so new ones show at the top
        $allTasks = Task::latest()->get();

        return view('tasks', [
            'tasks' => $allTasks
        ]);
    }

    /**
     * Store a newly created task in the database.
     */
    public function store(Request $request)
    {
        // 1. Form Validation: Stop empty or massive text fields from entering our database
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // 2. Database Insertion: Create a record using our Eloquent model properties
        Task::create([
            'title' => $validatedData['title'],
            'is_completed' => false, // New tasks default to pending status
        ]);

        // 3. User Redirect: Send the browser back to our task list to view the update
        return redirect('/tasks');
    }
}
