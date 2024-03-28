<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

Route::middleware('auth.jwt')->group(function () {
    Route::post('/tasks/create', [TaskController::class, 'create']);
});
Route::get('/users', [TaskController::class, 'getAllUsers']);
Route::get('/tasks/report', [TaskController::class, 'generateReport'])->name('tasks.generateReport');
Route::get('/tasks/all', [TaskController::class, 'getAllTasks'])->name('tasks.all');
// Route::get('/tasks/{taskId}/comments', [TaskCommentController::class, 'index']);
// Route::delete('/comments/{commentId}', [TaskCommentController::class, 'destroy']);
Route::get('/tasks/{taskId}', [TaskController::class, 'getTaskById']);
