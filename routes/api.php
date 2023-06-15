<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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
Route::get('products/{category_id}', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);