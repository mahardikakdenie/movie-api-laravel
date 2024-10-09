<?php

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('media')->middleware('auth:sanctum')->group(function () {
    Route::post('/upload', [ImageUploadController::class, 'uploadImage']);
});

Route::prefix('movie')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::post('/', [MovieController::class, 'store']);
    Route::put('/{id}', [MovieController::class, 'update']);
});
