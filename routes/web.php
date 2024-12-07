<?php

use App\Http\Controllers\TaskController;
use App\Exports\TasksExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('login', [TaskController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Route Dashboard dengan nama 'tasks.index'
    Route::get('/dashboard', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');


    // Route Ekspor Data ke Excel dengan nama 'tasks.export'
    Route::get('/tasks/export', function () {
        return Excel::download(new TasksExport, 'tasks.xlsx');
    })->name('tasks.export');

    // Route Ekspor Data ke PDF dengan nama 'tasks.export-pdf'
    Route::get('/tasks/export-pdf', [TaskController::class, 'exportPdf'])->name('tasks.export-pdf');
});

Route::post('logout', [TaskController::class, 'logout']);
