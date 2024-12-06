<?php

use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('login', [TaskController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Memberikan nama pada route dashboard
    Route::get('/dashboard', [TaskController::class, 'index'])->name('tasks.index'); // Nama route 'tasks.index'
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});

Route::post('logout', [TaskController::class, 'logout']);
