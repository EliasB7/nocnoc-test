<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAttachmentController;
use App\Http\Controllers\TaskCommentController;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password/{token}', [AuthController::class, 'resetPassword']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('createTask', [AuthController::class, 'createTask']);
    Route::post('register-admin', [AuthController::class, 'registerSuperAdmin']);
    Route::delete('/tasks/{taskId}', [AuthController::class, 'delete']);
    Route::put('/tasks/{taskId}', [AuthController::class, 'updateTask']);
    Route::post('tasks/{taskId}/comments', [TaskController::class, 'createComment']);
    Route::post('tasks/{taskId}/attachments', [TaskAttachmentController::class, 'attach']);
    Route::delete('attachments/{attachmentId}', [TaskAttachmentController::class, 'destroy']);
    Route::get('attachments/{attachmentId}/download', [TaskAttachmentController::class, 'download']);
    Route::get('/attachments/{attachmentId}', [TaskAttachmentController::class, 'findById']);
    Route::get('tasks/{taskId}/comments', [TaskCommentController::class, 'index']);
    Route::delete('/tasks/comments/{commentId}', [TaskCommentController::class, 'destroy']);
    Route::post('tasks/{taskId}/attachments', [TaskAttachmentController::class, 'attach']);
    Route::delete('attachments/{attachmentId}', [TaskAttachmentController::class, 'destroy']);
    Route::get('tasks/{taskId}/attachments', [TaskAttachmentController::class, 'index']);
    Route::get('/generate-report', [AuthController::class, 'generateReport']);
});

// Rutas protegidas por el middleware 'auth:sanctum'
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
