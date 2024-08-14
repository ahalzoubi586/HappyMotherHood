<?php

use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\API\UserController;
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

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);
Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/getAllCategories', 'getAllCategories');
    });
    Route::controller(BlogController::class)->group(function () {
        Route::post('/getAllBlogs', 'getAllBlogs');
        Route::post('/getBlogDetails', 'getBlogDetails');
    });
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations', 'index');
        Route::post('/conversations', 'store');
        Route::patch('/conversations/{id}/read', 'markAsRead');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/search', 'search');
    });
    Route::controller(IndexController::class)->group(function () {
        Route::post('/updateDuration', 'updateDuration');
    });
});
