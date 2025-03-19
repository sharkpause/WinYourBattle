<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->email],
        [
            'username' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'image' => $googleUser->avatar,
            'bio' => 'This user has not set a bio yet 🤔',
            'email_verified_at' => now(),
            'password' => bcrypt(str()->random(12)), // Random password (not used)
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});

Route::redirect('/', 'posts', 301);

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

Route::get('/{user_id}/posts', [DashboardController::class, 'userPosts'])->name('posts.user');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

    Route::put('/set-new-relapse', [DashboardController::class, 'setNewRelapse'])->name('set-new-relapse');
    Route::put('/set-initial-relapse-date', [DashboardController::class, 'setInitialRelapseDate'])->name('set-initial-relapse-date');
    Route::delete('/reset-relapse-data', [DashboardController::class, 'resetRelapseData'])->name('reset-relapse-data');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');
    Route::get('{user_id}/profile', [UserController::class, 'index'])->name('profile');

    Route::get('/get-statistics', [DashboardController::class, 'getStatistics'])->name('get-statistics');
    Route::post('/set-mood', [DashboardController::class, 'setMood'])->middleware('verified')->name('set-mood');
    Route::get('/get-mood', [DashboardController::class, 'getMood'])->name('get-mood');
    Route::post('/set-journal', [DashboardController::class, 'setJournal'])->middleware('verified')->name('set-journal');
    Route::get('/get-journal', [DashboardController::class, 'getJournal'])->name('get-journal');

    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');
    Route::put('/email/verify', [AuthController::class, 'verifyChangeEmail'])->name('verification.change-email');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class,'verifyEmail'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'verifyHandler'])->middleware('throttle:5,1')->name('verification.send');

    Route::get('/{user_id}/followers', [UserController::class, 'getFollowers'])->name('users.followers');
    Route::get('/{user_id}/followings', [UserController::class, 'getFollowings'])->name('users.followings');
    Route::get('/{user_id}/follow-requests', [UserController::class, 'getFollowRequests'])->name('users.follow-requests');

    Route::post('/{user_id}/follow-requests/{follower_id}/accept', [UserController::class, 'acceptFollowRequest'])->name('follow-request.accept');
    Route::delete('/{user_id}/follow-requests/{follower_id}/reject', [UserController::class, 'rejectFollowRequest'])->name('follow-request.reject');

    Route::get('/{user_id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/{user_id}/edit', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user_id}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::post('/{user_id}/follow', [UserController::class, 'follow'])->name('users.follow');
    Route::delete('/{user_id}/unfollow', [UserController::class, 'unfollow'])->name('users.unfollow');

    Route::post('/posts/{post_id}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post_id}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

    Route::post('/posts/{post_id}/dislike', [PostController::class, 'dislike'])->name('posts.dislike');
    Route::post('/posts/{post_id}/undislike', [PostController::class, 'undislike'])->name('posts.undislike');

    Route::get('/posts/following', [PostController::class, 'following'])->name('posts.following');

    Route::post('/posts/{post_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post_id}/comments', [CommentController::class, 'show'])->name('comments.show');
    
    Route::post('/posts/{post_id}/comments/{comment_id}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/posts/{post_id}/comments/{comment_id}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');
    Route::post('/posts/{post_id}/comments/{comment_id}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');
    Route::post('/posts/{post_id}/comments/{comment_id}/undislike', [CommentController::class, 'undislike'])->name('comments.undislike');

    Route::delete('/posts/{post_id}/comments/{comment_id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/posts/{post_id}/comments/{comment_id}', [CommentController::class, 'update'])->name('comments.update');
});

Route::resource('posts', PostController::class);