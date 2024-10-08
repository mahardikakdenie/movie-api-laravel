<?php

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
    // return 'userrr';
})
    ->middleware('auth:sanctum');
