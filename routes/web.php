<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Automatically redirect the root homepage to our tasks list dashboard
Route::get('/', function () {
    return redirect('/tasks');
});

// Display the list of tasks (READ)
Route::get('/tasks', [TaskController::class, 'index']);

// Handle form submission to add a new task (CREATE)
Route::post('/tasks', [TaskController::class, 'store']);

// Toggle task complete/incomplete status (UPDATE)
Route::put('/tasks/{task}', [TaskController::class, 'update']);

// Permanently wipe out a task row (DELETE)
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
