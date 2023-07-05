<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('categories', [CategoryController::class, 'create']);
Route::post('categories/{id}/restore', [CategoryController::class, 'restore']);
Route::patch('categories/{id}/update', [CategoryController::class, 'update']);
Route::delete('categories/{id}/delete', [CategoryController::class, 'destroy']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

Route::post('products', [ProductController::class, 'create']);
Route::post('products/{id}/restore', [ProductController::class, 'restore']);
Route::patch('products/{id}/update', [ProductController::class, 'update']);
Route::delete('products/{id}/delete', [ProductController::class, 'destroy']);
Route::get('products/{category_id}/index', [ProductController::class, 'index']);
Route::get('products/{id}/show', [ProductController::class, 'show']);

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
Route::get('users', [AuthController::class, 'index']);
Route::delete('users/{id}/delete', [AuthController::class, 'destroy']);
Route::post('users/{id}/restore', [AuthController::class, 'restore']);
Route::patch('users/{id}/update', [AuthController::class, 'update']);