<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Statistic;
use App\Models\Quote;
use App\Models\RelapseTrack;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $posts = Auth::user()->posts()->latest()->paginate(6);
        $statistics = Auth::user()->statistics;
        $quote = (Quote::inRandomOrder()->take(1)->get())[0];
        
        return view('users.dashboard', ['posts' => $posts, 'statistics' => $statistics, 'quote' => $quote]);
    }

    public function setInitialRelapseDate(Request $request) {
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

        $date = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                Carbon::parse($request->time_of_relapse),
                $request->timezone)->setTimezone('UTC');

        /*  The code above basically takes the time of relapse from the request,
            then creates a date object from it in the format of Y-m-d H:i:s in the user's time zone.
            Then it converts it from the original timezone to UTC timezone to be saced in the database.
            This is needed because when the user submits a time from the front end, the backend thinks
            the timezone is already UTC when it is not.
        */

        Auth::user()->statistics()->create(['date_of_relapse' => $date, 'timezone' => $request->timezone]);
        Auth::user()->relapseTracks()->create(['relapse_date' => $date]);

        return redirect('dashboard');
    }

    public function setNewRelapse(Request $request) {
        $user = Auth::user();
        $lastRelapse = $user->relapseTracks()->latest('relapse_date')->first();
        $streakTime = abs(Carbon::now()->diffInSeconds($lastRelapse->relapse_date));
        
        $lastRelapse->update(['streak_time' => $streakTime]);

        $date = now()->format('Y-m-d H:i:s');
    
        $user->relapseTracks()->create(['relapse_date' => $date]);
        Auth::user()->statistics()->update(['date_of_relapse' => $date]);

        return redirect('dashboard');
    }

    public function userPosts(User $user) {
        $userPosts = $user->posts()->latest()->paginate(6);

        return view('users.posts', ['posts' => $userPosts, 'username' => $user->username]);
    }

    public function getStatistics() {
        $data = Auth::user()->relapseTracks()->latest()->take(8)->get()->reverse();

        $data->each(function ($item) {
            $item->relapse_date = Carbon::parse($item->your_datetime_field)->timezone(Auth::user()->statistics()->value('timezone'));
        });

        return response()->json($data);
    }
}
