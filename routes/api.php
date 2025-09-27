<?php

use App\Http\Controllers\Api\ArtController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
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
            Route::put('update-job-post/{job_post_id}', 'updateJobPost');
            Route::delete('delete-job-post/{job_post_id}', 'deleteJobPost');
            Route::post('follow/unfollow-profile/{business_profile_id}', 'profileFollow');
            Route::get('view-profile/{business_profile_id}', 'viewProfile');
            Route::get('profile-count', 'ProfileCount');
        });
    });
});
Route::prefix('job')->group(function () {
    Route::group(['controller'=>JobController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('get-job-post', 'getJobPost');
            Route::get('view-job-post/{Job_post_id}', 'viewJobPost');
            Route::post('apply-now/{Job_post_id}', 'applyNow');
            Route::post('saved/unsaved-job/{Job_post_id}', 'savedUnsavedJob');
            Route::get('applied-jobs', 'appliedJobs');
            Route::get('saved-jobs', 'savedJobs');
        });
    });
});
Route::prefix('post')->group(function () {
    Route::group(['controller'=>PostController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('get-post', 'getPost');
            Route::post('create-post', 'createPost');
            Route::post('like/{post_id}', 'like');
            Route::post('comment/{post_id}', 'comment');
            Route::post('reply-comment/{post_id}/{comment_id}', 'replyComment');
            Route::post('share/{post_id}', 'share');

            Route::post('comment-like/{post_id}/{comment_id}', 'commentLike');
            Route::post('reply-comment-like/{post_id}/{comment_id}/{reply_id}', 'replyCommentLike');
            Route::get('get-saved', 'getSaved');
            Route::post('saved-unsaved/{post_id}', 'savedUnsaved');
        });
    });
});
Route::prefix('message')->group(function () {
    Route::group(['controller'=>MessageController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('chat-list',  'chatList');
            Route::get('chat/{receiver_id}',  'getOrCreateChat');
            Route::post('send-message',  'sendMessage');
            Route::get('chat-messages/{chat_id}',  'fetchMessages');
            Route::patch('chat-read/{chat_id}',  'markMessagesAsRead');
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
