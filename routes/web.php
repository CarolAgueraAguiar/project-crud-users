<?php

use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function ($token) {
    $email = request()->query('email');
    return view('auth.reset-password', ['token' => $token, 'email' => urldecode($email)]);
})->name('password.reset');
