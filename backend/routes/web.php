<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;



// Public Routes
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('auth.showRegistrationForm');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');


// OAuth routes for Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google_callback');


// Protected Routes
Route::group(['middleware' => ['login']], function () {
    // Protected routes for Super Admin only
    Route::group(['middleware' => ['role:Super Admin']], function () {
        Route::delete('/users/ajax_destroy/{id}', [UserController::class, 'ajaxDestroy'])->name('users.ajaxDestroy');
    });


    // Protected routes for Super Admin, and Admin
    Route::group(['middleware' => ['role:Super Admin|Admin']], function () {
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    });


    // Protected routes for any authenticated user
    // Dashboard Route
    Route::get('/', [AuthController::class, 'home'])->name('auth.home');
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('auth.dashboard');


    // User Related Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/ajax_show/{id}', [UserController::class, 'ajaxShow'])->name('users.ajaxShow');


    // Logout Route
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});
