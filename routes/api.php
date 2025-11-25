<?php

use App\Http\Controllers\Api\ArtController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\BusinessProfileController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupPostController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\JobManagementController;
use App\Http\Controllers\Api\NetworkController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserManagementController;
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
            Route::put('update-avatar','updateAvatar');
            Route::put('password-reset', 'resetPassword');
            Route::post('logout','logout');
        });
    });
});
Route::prefix('home')->group(function () {
    Route::group(['controller'=>HomeController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('search', 'search');
            Route::post('report', 'report');
            Route::get('suggest-group', 'suggestGroup');
            Route::get('online-user', 'onlineUser');
        });
    });
});
Route::prefix('network')->group(function () {
    Route::group(['controller'=>NetworkController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('connections', 'connections');
            Route::get('discovers', 'discovers');
            Route::get('requests', 'requests');
            Route::get('visitors', 'visitors');
        });
    });
});
Route::prefix('user')->group(function () {
    Route::group(['controller'=>UserController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('info', 'getUserInfo');
            Route::put('info', 'updateUserInfo');
            Route::put('info-cover', 'updateUserInfoCover');
            Route::get('experience', 'getUserExperience');
            Route::get('single-experience/{experience_id}', 'singleExperience');
            Route::post('create-experience', 'createUserExperience');
            Route::put('experience/{experience_id}', 'updateUserExperience');
            Route::get('gallery', 'getUserGallery');
            Route::post('gallery', 'updateUserGallery');
            Route::delete('gallery/{gallery_id}', 'deleteGallery');
            Route::get('education', 'getUserEducation');
            Route::get('single-education/{education_id}', 'singleEducation');
            Route::post('create-education', 'createUserEducation');
            Route::put('education/{education_id}', 'updateUserEducation');
            Route::get('skill', 'getUserSkill');
            Route::post('skill', 'createUserSkill');
            Route::delete('delete-skill/{skill_id}', 'deleteUserSkill');
            Route::get('connection', 'getUserConnection');
            Route::post('connection/{connection_id}', 'userConnection');
            Route::post('accept-connection/{connection_id}', 'acceptUserConnection');
            Route::post('cancel-connection/{connection_id}', 'cancelUserConnection');
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
            Route::get('single-post/{post_id}', 'singlePost');
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
            Route::post('send-message/{chat_id}',  'sendMessage');
            Route::get('chat-messages/{chat_id}',  'fetchMessages');
            Route::patch('chat-read/{chat_id}',  'markMessagesAsRead');
            Route::post('actions/{chat_id}',  'performActions');
        });
    });
});
Route::prefix('group')->group(function () {
    Route::group(['controller'=>GroupPostController::class],function(){
        Route::middleware(['auth:sanctum','user'])->group(function () {
            Route::get('view/{group_id}',  'viewGroup');
            Route::get('invite-member',  'inviteMember');
            Route::post('create',  'createGroup');
            Route::post('join/{group_id}',  'joinGroup');
            Route::get('get-post', 'getPost');
            Route::post('create-post/{group_id}', 'createPost');
            Route::post('like/{group_id}/{group_post_id}', 'like');
            Route::post('comment/{group_id}/{group_post_id}', 'comment');
            Route::post('share/{group_id}/{group_post_id}', 'share');
            Route::post('comment-like/{group_id}/{group_post_id}/{group_comment_id}', 'commentLike');
            Route::post('reply-comment/{group_id}/{group_post_id}/{group_comment_id}', 'replyComment');
            Route::post('reply-comment-like/{group_id}/{group_post_id}/{group_comment_id}/{reply_id}', 'replyCommentLike');
            Route::post('saved-unsaved/{group_id}/{group_post_id}', 'savedUnsaved');

            // Route::get('get-saved', 'getSaved');
        });
    });
});
Route::prefix('notification')->group(function () {
    Route::group(['controller' => NotificationController::class], function () {
        Route::middleware(['auth:sanctum', 'user'])->group(function () {
            Route::get('index', 'index');
            Route::post('read/{id}', 'markAsRead');
            Route::post('read-all', 'markAllRead');
        });
    });
});
//admin panel
Route::prefix('user-management')->group(function () {
    Route::group(['controller'=>UserManagementController::class],function(){
        Route::middleware(['auth:sanctum','admin'])->group(function () {
            Route::get('get-user', 'getuser');
            Route::put('ban-user/{id}', 'banUser');
            Route::get('view-user/{id}', 'viewUser');
            Route::delete('delete-user/{id}', 'deleteUser');
        });
    });
});
Route::group(['controller'=>ArtController::class],function(){
    Route::get('get-art', 'getArt');
    Route::middleware(['auth:sanctum','admin'])->group(function () {
        Route::post('create-art', 'createArt');
        Route::delete('delete-art/{art_id}', 'deleteArt');
    });
});
Route::prefix('business-profile')->group(function () {
    Route::group(['controller'=>BusinessProfileController::class],function(){
        Route::middleware(['auth:sanctum','admin'])->group(function () {
            Route::get('get-user', 'getuser');
            Route::get('view-user/{id}', 'viewUser');
            Route::put('post-permission/{id}', 'postPermission');
            Route::put('business-features/{id}', 'businessFeature');
        });
    });
});
Route::prefix('job-management')->group(function () {
    Route::group(['controller'=>JobManagementController::class],function(){
        Route::middleware(['auth:sanctum','admin'])->group(function () {
            Route::get('get-job', 'getJob');
            Route::get('view-job/{id}', 'viewJob');
            Route::put('approve/{id}', 'approve');
            Route::put('reject/{id}', 'reject');
            Route::delete('delete/{id}', 'delete');
        });
    });
});
