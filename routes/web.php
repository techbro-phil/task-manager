<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// The homepage sends logged-in users straight to their task dashboard.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('tasks.index') : view('welcome');
});

Route::middleware('auth')->group(function () {
    // Generates: tasks.index (GET /tasks), tasks.store (POST /tasks),
    // tasks.update (PUT /tasks/{task}), tasks.destroy (DELETE /tasks/{task})
    Route::resource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';