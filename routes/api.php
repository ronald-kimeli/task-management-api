<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TasksController;
use App\Http\Controllers\API\CategoriesController;

# Auth & public
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::apiResource('tasks', TasksController::class)->only(['index', 'show']);

    Route::apiResource('categories', CategoriesController::class)->only(['index', 'show']);
});

//Protected routes
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TasksController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('categories', CategoriesController::class)->only(['store', 'update', 'destroy']);
    Route::prefix('tasks')->group(function () {
        Route::put('/{task}/status', [TasksController::class, 'updateStatus']);
        Route::post('/{task}/upload', [TasksController::class, 'uploadImage']);
    });
});

