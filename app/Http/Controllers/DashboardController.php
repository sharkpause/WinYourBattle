<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $posts = Auth::user()->posts()->latest()->paginate(6);
        
        return view('users.dashboard', ['posts' => $posts]);
    }

    public function setRelapseDate(Request $request) {
        $fields = $request->validate([
            'relapseDate' => ['required', 'date']
        ]);
        
        // Modify row to include relapse date

        return redirect('dashboard');
    }

    public function userPosts(User $user) {
        $userPosts = $user->posts()->latest()->paginate(6);

        return view('users.posts', ['posts' => $userPosts, 'username' => $user->username]);
    }
}
