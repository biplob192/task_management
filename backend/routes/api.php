<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController as AuthControllerForApi;
use App\Http\Controllers\Api\V1\TaskController as TaskControllerForApi;
use App\Http\Controllers\Api\V1\UserController as UserControllerForApi;
use App\Http\Controllers\Api\V1\GoogleController as GoogleControllerForApi;



// Public routes for any user
Route::post('register', [AuthControllerForApi::class, 'register'])->name('api.register');
Route::post('login', [AuthControllerForApi::class, 'login'])->name('api.login');


// OAuth routes for Google
Route::get('auth/google', [GoogleControllerForApi::class, 'redirectToGoogle'])->name('api.google');
Route::get('auth/google/callback', [GoogleControllerForApi::class, 'handleGoogleCallback'])->name('api.google_callback');


// Protected routes
Route::group(['middleware' => ['auth:api', 'scopes:access', 'checkTokenExpiry'], 'as' => 'api.'], function () {
    // Protected routes for Super Admin only
    Route::group(['middleware' => ['auth:api', 'role:Super Admin']], function () {
        Route::post('users', [UserControllerForApi::class, 'store'])->name('users.store');
        Route::put('users/{id}', [UserControllerForApi::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserControllerForApi::class, 'destroy'])->name('users.destroy');
    });


    // Protected routes for Admin, Super Admin, Employee and User
    Route::group(['middleware' => ['auth:api', 'role:Super Admin|Admin|Employee|User']], function () {
        Route::get('users', [UserControllerForApi::class, 'index'])->name('users.index');
        Route::get('users/{id}', [UserControllerForApi::class, 'show'])->name('users.show');
    });


    // Protected routes for any authenticated user
    Route::apiResource('tasks', TaskControllerForApi::class);


    // Auth and validation related routes
    Route::get('/validate-token', [AuthControllerForApi::class, 'validateToken']);
    Route::post('logout', [AuthControllerForApi::class, 'logout'])->name('logout');
});


// Refresh token route | re-generate the access and the refresh token
Route::post('refresh', [AuthControllerForApi::class, 'refresh'])->name('refresh')->middleware(['auth:api', 'scopes:refresh']);
