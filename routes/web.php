<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', 'posts', 301);

Route::resource('posts', PostController::class);

Route::middleware(['guest'])->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/{user}/posts', [DashboardController::class, 'userPosts'])->name('posts.user');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

    Route::put('/set-initial-relapse-date', [DashboardController::class, 'setInitialRelapseDate'])->name('set-initial-relapse-date');
    Route::put('/set-new-relapse', [DashboardController::class, 'setNewRelapse'])->name('set-new-relapse');
    
    Route::get('/get-statistics', [DashboardController::class, 'getStatistics'])->name('get-statistics');
});