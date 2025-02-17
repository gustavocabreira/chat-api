<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('api')->name('api.')->group(function () {
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/auth/login', [LoginController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [UserController::class, 'me'])->name('users.me');
    });
});
