<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
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
});
