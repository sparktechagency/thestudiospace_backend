<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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
Route::prefix('user')->group(function () {
    Route::group(['controller'=>UserController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('info', 'getUserInfo');
            Route::put('info', 'updateUserInfo');
            Route::get('experience', 'getUserExperience');
            Route::put('experience', 'updateUserExperience');
            Route::get('gallery', 'getUserGallery');
            Route::put('gallery', 'updateUserGallery');
            Route::get('education', 'getUserEducation');
            Route::put('education', 'updateUserEducation');
            Route::get('skill', 'getUserSkill');
            Route::post('skill', 'updateUserSkill');
        });
    });
});

