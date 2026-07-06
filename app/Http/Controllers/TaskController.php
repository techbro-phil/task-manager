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
        // Eloquent pulls ALL records from the tasks table automatically
        $allTasks = Task::all();

        // Pass the database tasks directly into our existing 'tasks.blade.php' layout
        return view('tasks', [
            'tasks' => $allTasks
        ]);
    }
}
