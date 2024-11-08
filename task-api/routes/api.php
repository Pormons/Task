<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Task Routes
// Fetch All Tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');

// Create Task
Route::post('/task', [TaskController::class, 'store'])->name('task.store');

// Fetch Specified Task
Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');

// Update Specified Task
Route::patch('/task/{id}', [TaskController::class, 'update'])->name('task.update');

// Update Specified Task
Route::patch('/task/status/{id}', [TaskController::class, 'statusUpdate'])->name('task.status');

Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
