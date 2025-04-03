<?php

use Application\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'store'])->name('store');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/all', [UserController::class, 'index']);
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'destroy']);
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
