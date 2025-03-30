<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeshboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SessionAuthenticateMiddleware;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', [HomeController::class, "index"])->name('home');

//user backend point
Route::post('/user-registration', [UserController::class, "userRegistration"])->name('user.registration');
Route::post('/user-login', [UserController::class, "userlogin"])->name('user.login');
Route::post('/send-otp', [UserController::class, "SendOTPCode"]);
Route::post('/verify-otp', [UserController::class, "VerifyOTP"]);


// route::middleware(TokenVerificationMiddleware::class)->group(function () {
route::middleware(SessionAuthenticateMiddleware::class)->group(function () {
    Route::get('/DeshboardPage', [DeshboardController::class, "DeshboardPage"])->name('Deshboard.Page');
    Route::get('/logout', [UserController::class, "logout"])->name('logout.Page');
    Route::post('/Reset-Password', [UserController::class, "resetPassword"]);

    //category API point
    Route::post('/create-category', [CategoryController::class, "CreateCategory"])->name('create.category');
    Route::get('/list-category', [CategoryController::class, "CategoryList"])->name('list.category');
    Route::post('/category-by-id', [CategoryController::class, "CategoryById"]);
    Route::post('/update-category', [CategoryController::class, "CategoryUpdate"])->name('update.category');
    Route::get('/Delete-category/{id}', [CategoryController::class, "CategoryDelete"])->name('Delete.category');
    //category page 
    Route::get('/category-page', [CategoryController::class, "CategoryPage"])->name('CategoryPage');
    Route::get('/CategorySavePage', [CategoryController::class, "CategorySavePage"])->name('CategoryPage');

    //Brand API point
    Route::post('/create-brand', [BrandController::class, "CreateBrand"])->name('create.Brand');
    Route::get('/list-brand', [BrandController::class, "BrandList"])->name('list.Brand');
    Route::post('/brand-by-id', [BrandController::class, "BrandById"]);
    Route::post('/brand-update', [BrandController::class, "BrandUpdate"])->name('update.Brand');
    Route::get('/delete-brand/{id}', [BrandController::class, "BrandDelete"])->name('Delete.Brand');
    //Brand page 
    Route::get('/Brand-page', [BrandController::class, "BrandPage"])->name('BrandPage');
    Route::get('/BrandSavePage', [BrandController::class, "BrandSavePage"]);


    //Products API point
    Route::post('/create-Product', [ProductController::class, "CreateProduct"])->name('create.Product');
    Route::get('/list-Product', [ProductController::class, "ProductList"])->name('list.Product');
    Route::post('/Product-by-id', [ProductController::class, "ProductById"]);
    Route::post('/update-product', [ProductController::class, "ProductUpdate"])->name('update.Product');
    Route::get('/Delete-Product/{id}', [ProductController::class, "ProductDelete"])->name('Delete.Product');

    //Product page
    Route::get('/product-page', [ProductController::class, "ProductPage"])->name('Product.Page');
    Route::get('/ProductSavePage', [ProductController::class, "ProductSavePage"])->name('ProductSave.Page');

    //Customer API point
    Route::post('/create-customer', [CustomerController::class, "Createcustomer"])->name('create.customer');
    Route::get('/list-customer', [CustomerController::class, "customerList"])->name('list.customer');
    Route::post('/customer-by-id', [CustomerController::class, "customerById"]);
    Route::post('/update-customer', [CustomerController::class, "customerUpdate"])->name('update.customer');
    Route::get('/Delete-customer/{id}', [CustomerController::class, "customerDelete"])->name('Delete.customer');
    //Customer page
    Route::get('/customer-page', [CustomerController::class, "customerPage"])->name('customer.Page');
    Route::get('/CustomerSavePage', [CustomerController::class, "CustomerSavePage"])->name('CustomerSave.Page');


    //Invoice API point
    Route::post('/invoice-create', [InvoiceController::class, "InvoiceCreate"])->name('create.invoice');
    Route::get('/list-invoice', [InvoiceController::class, "InvoiceList"])->name('list.invoice');
    Route::post('/details-invoice', [InvoiceController::class, "InvoiceDetails"])->name('details.invoice');
    Route::get('/invoice-delete/{id}', [InvoiceController::class, "InvoiceDelete"])->name('delete.invoice');

    //Invoice page
    Route::get('/invoice-page', [InvoiceController::class, "InvoicePage"])->name('invoice.Page');

    //create-sale
    Route::get('/create-sale', [SaleController::class, "SalePage"])->name('Sale.Page');
    // Deshboard Summary APi point 
    Route::get('/deshboard-summary', [DeshboardController::class, 'deshboardSummary']);

    //reset password 
    Route::get('/reset-password', [UserController::class, 'ResetPasswordPage']);
    //profile page
    Route::get('/profilePage', [UserController::class, 'ProfileInfo'])->name('Profile.Page');
    Route::post('/user-update', [UserController::class, 'UpdateProfile'])->name('update.profile');
});

//Page Route 
Route::get('/login', [UserController::class, "LoginPage"])->name('loginPage');
Route::get('/registration', [UserController::class, "registrationPage"])->name('registrationPage');
Route::get('/send-otp', [UserController::class, 'SendOTPPage'])->name('SendOTPPage');
Route::get('/verify-OTPPage', [UserController::class, 'VerifyOTPPage'])->name('VerifyOTPPage');
