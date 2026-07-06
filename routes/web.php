<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

// The homepage checks if a user is authenticated
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/tasks');
    }
    return view('welcome');
});

// Protected routes wrapped in the 'auth' middleware group
Route::middleware('auth')->group(function () {
    // Task Manager CRUD routes
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    
    // Category routes (We only need 'store' for adding labels)
    Route::post('/categories', [CategoryController::class, 'store']);
    
    // Account Profile management actions
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
