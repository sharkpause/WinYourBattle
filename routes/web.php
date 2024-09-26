<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'posts.index')->name('home');
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/set-timezone', [DashboardController::class, 'setTimeZone']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');