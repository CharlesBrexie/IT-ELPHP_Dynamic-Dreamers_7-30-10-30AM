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


Route::get('/api/users', [UserController::class, 'index']);
Route::get('/api/users/{id}', [UserController::class, 'getUserDetails']);
Route::post('/api/users', [UserController::class, 'store']);
Route::patch('/api/users/{id}', [UserController::class, 'updateUserDetails']);
Route::delete('/api/users/{id}', [UserController::class, 'destroy']);

// DONATIONS
Route::get('/api/donations', [DonationController::class, 'get']);
Route::get('/api/users/{id}/donations', [DonationController::class, 'index']);
Route::post('/api/users/{id}/donations', [DonationController::class, 'store']);
Route::get('/api/users/{userId}/donations/{donationId}', [DonationController::class, 'show']);
Route::delete('/api/users/{userId}/donations/{donationId}', [DonationController::class, 'destroy']);

// REQUEST
Route::get('/api/users/{id}/requests', [RequestController::class, 'index']);
Route::post('/api/users/{ngoId}/donations/{donationId}', [RequestController::class, 'store']);
Route::delete('/api/users/{ngoId}/requests/{donationId}', [RequestController::class, 'destroy']);
