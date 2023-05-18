<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

// EndPoint REGISTER
Route::controller(RegisterController::class)->group(function () {
    Route::post('/validate-email', 'validateEmail')->name('auth.validate-email');
    Route::post('/validate-names', 'validateNames')->name('auth.validate-names');
    Route::post('/validate-password', 'validatePassword')->name('auth.validate-password');
    Route::post('/confirm-email', 'confirmEmail')->name('auth.confirmEmail');
    Route::post('/register', 'registerUser')->name('auth.register');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', 'logout')->name('auth.logout');
    });
    Route::post('/forget-password', 'forgetPassword')->name('auth.forgetPassword');
    Route::post('/reset-password', 'resetPassword')->name('auth.resetPassword');
    Route::get('/token/{token}', 'token')->name('auth.token');
});

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);

