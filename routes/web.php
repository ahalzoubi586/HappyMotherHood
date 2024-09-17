<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('PrivacyPolicy', function () {
    return view('Pages.General.privacy_' . app()->getLocale());
})->name('privacy_policy');
Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});
Route::controller(AccountController::class)->group(function () {
    Route::get('/DeleteMyAccount', 'showDeleteAccountForm')->name('delete-account-form');
    Route::post('/delete-account', 'deleteAccount')->name('delete-account');
});
Route::group(['middleware' => ['auth']], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/Dashboard', 'index')->name('dashboard.index');
    });
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/Dashboard', 'index')->name('dashboard.index');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/Categories', 'index')->name('categories.index');
        Route::get('/CategoriesList', 'categories_list')->name('categories.list');
        Route::get('/CategoriesCreate', 'create')->name('categories.create');
        Route::post('/CategoriesStore', 'store')->name('categories.store');
        Route::get('/CategoriesEdit/{category_id}', 'edit')->name('categories.edit');
        Route::post('/CategoriesUpdate', 'update')->name('categories.update');
    });
    Route::controller(BlogController::class)->group(function () {
        Route::get('/Blogs', 'index')->name('blogs.index');
        Route::get('/BlogsList', 'blogs_list')->name('blogs.list');
        Route::get('/BlogsCreate/{category_id?}', 'create')->name('blogs.create');
        Route::post('/BlogsStore', 'store')->name('blogs.store');
        Route::get('/BlogsEdit/{blog_id}', 'edit')->name('blogs.edit');
        Route::post('/BlogsUpdate', 'update')->name('blogs.update');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('/Users', 'index')->name('users.index');
        Route::get('/UsersList', 'users_list')->name('users.list');
        Route::post('/UsersDetails', 'viewMoreData')->name('users.details');
    });
    Route::post('ckeditor/upload', [GeneralController::class, 'upload'])->name('ckeditor.upload');
});
