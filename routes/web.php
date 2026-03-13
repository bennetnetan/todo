<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('todos.index')
        : view('landing');
});

Route::get('/dashboard', function () {
    return redirect()->route('todos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// application routes protected by auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // todo resource
    Route::resource('todos', App\Http\Controllers\TodoController::class);
});

require __DIR__ . '/auth.php';
