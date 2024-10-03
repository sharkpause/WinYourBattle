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
            'date_of_relapse' => ['required', 'date'],
            'time_of_relapse' => ['required', 'date_format:H:i']
        ]);

        $dateString = $request->date_of_relapse . ' ' . $request->time_of_relapse . ':00';
        $date = new \Carbon\Carbon($dateString);

        $user = User::find(Auth::id());

        $user->date_of_relapse = $date;
        $user->save();

        return redirect('dashboard');
    }

    public function newRelapse(Request $request) {
        $date = now()->format('Y-m-d H:i');
    
        $user = User::find(Auth::id());

        $user->date_of_relapse = $date;
        $user->save();

        return redirect('dashboard');
    }

    public function userPosts(User $user) {
        $userPosts = $user->posts()->latest()->paginate(6);

        return view('users.posts', ['posts' => $userPosts, 'username' => $user->username]);
    }
}
