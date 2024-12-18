<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RiderController;
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

// Login and Registration Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'performLogin']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'performRegister']);

//Verify Email Address
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verifyEmail');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyMailToken'])->name('verifyMailToken');
Route::post('/verify-email', [AuthController::class, 'performVerifyEmail'])->name('verifyEmail.perform');

//Forgot Password
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/forgot-password', [AuthController::class, 'performForgotPassword'])->name('forgotPassword.perform');

//Reset Password
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/reset-password', [AuthController::class, 'performResetPassword'])->name('resetPassword.perform');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/products', [ProductsController::class, 'index'])->name('admin.products.dashboard');
    Route::get('/products/create', [ProductsController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('admin.products.destroy');

    //Riders
    Route::resource('riders', RiderController::class);
});
