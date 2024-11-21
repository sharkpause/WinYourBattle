<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::redirect('/', 'posts', 301);

Route::resource('posts', PostController::class);

Route::middleware(['guest'])->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail'])->name('password.email');
    Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');
});

Route::get('/{user}/posts', [DashboardController::class, 'userPosts'])->name('posts.user');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard'); 

    Route::put('/set-initial-relapse-date', [DashboardController::class, 'setInitialRelapseDate'])->name('set-initial-relapse-date');
    Route::put('/set-new-relapse', [DashboardController::class, 'setNewRelapse'])->name('set-new-relapse');
    
    Route::get('/get-statistics', [DashboardController::class, 'getStatistics'])->name('get-statistics');

    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class,'verifyEmail'])->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'verifyHandler'])->middleware('throttle:5,1')->name('verification.send');

    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/{id}/edit', [UserController::class, 'update'])->name('users.update');

    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
});