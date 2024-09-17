<?php

use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserController;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::get('/getGeneralSettings', 'getGeneralSettings');
});
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::post('/getAllCategories', 'getAllCategories');
        });
        Route::controller(BlogController::class)->group(function () {
            Route::post('/getAllBlogs', 'getAllBlogs');
            Route::post('/getBlogDetails', 'getBlogDetails');
        });
        Route::controller(ConversationController::class)->group(function () {
            Route::post('/getConversations', 'getConversations');
            Route::post('/sendMessage', 'sendMessage');
            Route::post('/getConversationMessages', 'getConversationMessages');
            Route::post('/getNewMessages', 'GetNewMessages');
        });
        Route::controller(UserController::class)->group(function () {
            Route::post('/getAllUsers', 'getAllUsers');
        });
        Route::controller(IndexController::class)->group(function () {
            Route::post('/updateDuration', 'updateDuration');
            Route::post('/fetchUnreadNotificationsAndMessagesCount', 'fetchUnreadNotificationsAndMessagesCount');
            Route::post('/changePassword', 'changePassword');
            Route::post('/SendVerificationCodeToEmail', 'SendVerificationCodeToEmail');
        });
        Route::controller(NotificationController::class)->group(function () {
            Route::post('/getUserNotifications', 'getUserNotifications');
        });
    },
);
