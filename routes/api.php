<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::group(['controller'=>AuthController::class],function(){
        Route::post('register',  'register');
        Route::post('verify-otp',  'verifyOtp');
        Route::post('resend-otp',  'resendOtp');
        Route::post('login',  'login');
        Route::get('google-login',  'googleLogin');
        Route::get('google/callback',  'googleCallback');
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('profile','getProfile');
            Route::put('profile-update','updateProfile');
            Route::put('password-reset', 'resetPassword');
            Route::post('logout','logout');
        });
    });
});

