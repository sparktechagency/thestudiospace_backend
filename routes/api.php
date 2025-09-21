<?php

use App\Http\Controllers\Api\ArtController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController;
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
            Route::post('skill', 'createUserSkill');
            Route::delete('delete-skill/{skill_id}', 'deleteUserSkill');
            Route::get('connection', 'getUserConnection');
            Route::post('connection/{connecton_id}', 'userConnection');
            Route::get('profile-view/{user_id}', 'userProfileView');
            Route::get('profile-count', 'userProfileCount');
        });
    });
});
Route::prefix('business')->group(function () {
    Route::group(['controller'=>BusinessController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('get-profile', 'getProfile');
            Route::post('profile', 'Profile');
            Route::get('get-gallery', 'getGallery');
            Route::put('gallery', 'Gallery');
            Route::get('get-job-post', 'getJobPost');
            Route::post('job-post', 'jobPost');
        });
    });
});
//admin panel
Route::group(['controller'=>ArtController::class],function(){
    Route::get('get-art', 'getArt');
    Route::middleware(['auth:sanctum','admin'])->group(function () {
        Route::post('create-art', 'createArt');
        Route::delete('delete-art/{art_id}', 'deleteArt');
    });
});
