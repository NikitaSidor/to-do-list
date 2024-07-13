<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::name('task.')->group(function () {
    Route::post('task/create', [TaskController::class, 'store'])
        ->name('create')->middleware(['auth', 'verified']);
    Route::post('task/update', [TaskController::class, 'update'])
        ->name('update')->middleware(['auth', 'verified']);
    Route::post('task/delete', [TaskController::class, 'delete'])
        ->name('delete')->middleware(['auth', 'verified']);
    Route::post('task/show', [TaskController::class, 'show'])
        ->name('show')->middleware(['auth', 'verified']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
