<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\OrderController;

// PUBLIC ROUTES (tanpa auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);

// UPDATE PROFILE (tanpa auth, pakai user_id)
Route::put('/user/update', [AuthController::class, 'update']);
Route::get('/me', [AuthController::class, 'me']);

// ORDER ROUTES (tanpa auth sementara)
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/my-orders', [OrderController::class, 'myOrders']);
Route::get('/orders/{orderCode}', [OrderController::class, 'show']);
Route::put('/orders/{orderCode}/status', [OrderController::class, 'updateStatus']);

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);