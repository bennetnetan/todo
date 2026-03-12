<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('todos.index');
});

// protect todo routes behind authentication
Route::middleware('auth')->group(function () {
    Route::resource('todos', TodoController::class);
});
