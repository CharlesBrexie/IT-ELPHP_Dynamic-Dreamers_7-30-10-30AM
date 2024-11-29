<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestController;
use GuzzleHttp\Psr7\Request;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/donations', [DonationController::class, 'index']);
    Route::get('/api/donations/{donationId}', [DonationController::class, 'show']);
    Route::post('/api/donations', [DonationController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/requests', [RequestController::class, 'index']);
    Route::get('/api/requests/{requestId}', [RequestController::class, 'show']);
    Route::post('/api/requests', [DonationController::class, 'store']);
});
