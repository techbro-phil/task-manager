<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Automatically redirect the root homepage to our tasks list dashboard
Route::get('/', function () {
    return redirect('/tasks');
});

// This displays the list of tasks (READ)
Route::get('/tasks', [TaskController::class, 'index']);

// This handles the form submission when someone adds a task (CREATE)
Route::post('/tasks', [TaskController::class, 'store']);
