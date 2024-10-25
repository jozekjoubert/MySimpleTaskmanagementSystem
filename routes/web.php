<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::post('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});
require __DIR__.'/auth.php';
