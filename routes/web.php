<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, "index"])->name('home');
Route::get('/test', [HomeController::class, "test"]);

//user backend point
Route::post('/user-registration', [UserController::class, "userRegistration"])->name('user.registration');
Route::post('/user-login', [UserController::class, "userlogin"])->name('user.login');
Route::post('/SendOTPPage', [UserController::class, "SendOTPCode"]);
Route::post('/verify-otp', [UserController::class, "VerifyOTP"]);


route::middleware(TokenVerificationMiddleware::class)->group(function () {
    Route::get('/DeshboardPage', [UserController::class, "DeshboardPage"])->name('Deshboard.Page');
    Route::get('/logout', [UserController::class, "logout"])->name('logout.Page');
    Route::post('/Reset-Password', [UserController::class, "resetPassword"]);

    //category API point
    Route::post('/create-category', [CategoryController::class, "CreateCategory"])->name('create.category');
    Route::get('/list-category', [CategoryController::class, "CategoryList"])->name('list.category');
    Route::post('/category-by-id', [CategoryController::class, "CategoryById"]);
    Route::post('/update-category', [CategoryController::class, "CategoryUpdate"])->name('update.category');
    Route::get('/Delete-category/{id}', [CategoryController::class, "CategoryDelete"])->name('Delete.category');

    //Products API point
    Route::post('/create-Product', [ProductController::class, "CreateProduct"])->name('create.Product');
    Route::get('/list-Product', [ProductController::class, "ProductList"])->name('list.Product');
    Route::post('/Product-by-id', [ProductController::class, "ProductById"]);
    Route::post('/update-product', [ProductController::class, "ProductUpdate"])->name('update.Product');
    Route::get('/Delete-Product/{id}', [ProductController::class, "ProductDelete"])->name('Delete.Product');

    //Customer API point
    Route::post('/create-customer', [CustomerController::class, "Createcustomer"])->name('create.customer');
    Route::get('/list-customer', [CustomerController::class, "customerList"])->name('list.customer');
    Route::post('/customer-by-id', [CustomerController::class, "customerById"]);
    Route::post('/update-customer', [CustomerController::class, "customerUpdate"])->name('update.customer');
    Route::get('/Delete-customer/{id}', [CustomerController::class, "customerDelete"])->name('Delete.customer');

});
