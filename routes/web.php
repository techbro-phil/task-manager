<?php

use Illuminate\Support\Facades\Route;

// This handles the main homepage (http://127.0.0.1:8000)
Route::get('/', function () {
    return view('welcome');
});

// This handles our new tasks page (http://127.0.0)
Route::get('/tasks', function () {
    // A temporary hardcoded list of tasks to simulate database data
    $myTasks = [
        'Set up local development environment',
        'Learn Laravel routing basics',
        'Create a beautiful Blade view template',
        'Design the database schema layout'
    ];

    // This passes the array data directly into a view file named 'tasks.blade.php'
    return view('tasks', [
        'tasks' => $myTasks
    ]);
});
