<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Auth\RegisterController;

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

// HOME
Route::get('/home', [HomeController::class, 'index'])->name('api.home');

// EndPoint REGISTER
Route::controller(RegisterController::class)->group(function () {
    Route::post('/validate-email', 'validateEmail')->name('auth.validate-email');
    Route::post('/validate-names', 'validateNames')->name('auth.validate-names');
    Route::post('/validate-password', 'validatePassword')->name('auth.validate-password');
    Route::post('/confirm-email', 'confirmEmail')->name('auth.confirmEmail');
    Route::post('/register', 'registerUser')->name('auth.register');
});

// EndPoint LOGIN
Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', 'logout')->name('auth.logout');
    });
    Route::post('/forget-password', 'forgetPassword')->name('auth.forgetPassword');
    Route::post('/reset-password', 'resetPassword')->name('auth.resetPassword');
});

// Endpoint PRODUCTS
Route::apiResource('products', ProductController::class)->except('update');
Route::post('/products/{product}', [ProductController::class, 'update']);

// EndPoint USERS
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('/profile/confirm-email', 'confirmEmail')->name('profile.confirmEmail');
        Route::middleware('email.verified')->group(function () {
            Route::get('/profile', 'getProfile')->name('profile.user');
            Route::post('/profile', 'update')->name('profile.update');
        });
    });
});

// EndPoint CATEGORIES
Route::apiResource('categories', CategoryController::class)->except('update', 'store', 'destroy');

// EndPoint SUBCATEGORIES
Route::controller(SubcategoryController::class)->group(function () {
    Route::get('/subcategories', 'index')->name('subcategories.index');
    Route::get('/categories/{categorySlug}/subcategories/{subcategorySlug}', 'show')->name('subcategories.show');
    Route::get('/categories/{categorySlug}/subcategories/{subcategorySlug}/products', 'getAllProducts')->name('subcategories.getAllProducts');
});

// EndPoint De el carrito
Route::controller(CartController::class)->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/add-cart', 'addToCart');
        Route::get('/view-cart', 'viewCart');
        Route::put('/update-cart', 'updateCartItem');
        Route::delete('/remove-cart/{id}', 'removeCartItem');
    });
});
