<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Routes for Authentication
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Routes for User Management (authenticated users only)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/users', [UserController::class, 'index']);
    Route::get('/api/users/{id}', [UserController::class, 'show']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::put('/api/users/{id}', [UserController::class, 'update']);
    Route::delete('/api/users/{id}', [UserController::class, 'destroy']);
});
