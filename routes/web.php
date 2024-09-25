<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'posts.index')->name('home');
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');

Route::post('/register', [AuthController::class, 'register']);