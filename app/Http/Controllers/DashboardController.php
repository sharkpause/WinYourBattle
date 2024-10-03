<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Statistic;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $posts = Auth::user()->posts()->latest()->paginate(6);
        $statistics = Auth::user()->statistics;
        
        return view('users.dashboard', ['posts' => $posts, 'statistics' => $statistics]);
    }

    public function setRelapseDate(Request $request) {
        $fields = $request->validate([
            'date_of_relapse' => ['required', 'date'],
            'time_of_relapse' => ['required', 'date_format:H:i:s'],
            'timezone' => ['required']
        ]);

        $exists = Statistic::where('user_id', Auth::user()->id)->exists();
        if($exists) {
            return back()->withErrors([
                'statistic_failed' => "Can't set a new relapse date, use new-relapse API instead."
            ]);
        }

        $dateString = Carbon::parse($request->time_of_relapse)->setTimezone($request->timezone);
        $date = (new Carbon($dateString))->setTimezone($request->timezone);

        Auth::user()->statistics()->create(['date_of_relapse' => $date, 'timezone' => $request->timezone]);
        
        return redirect('dashboard');
    }

    public function newRelapse(Request $request) {
        $date = now()->format('Y-m-d H:i:s');
    
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
