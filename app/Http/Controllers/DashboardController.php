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
        $quote = (Quote::inRandomOrder()->take(1)->get())[0];
        
        return view('users.dashboard', ['posts' => $posts, 'quote' => $quote]);
    }

    public function setInitialRelapseDate(Request $request) {
        $fields = $request->validate([
            'date_of_relapse' => ['required', 'date'],
            'time_of_relapse' => ['required', 'date_format:H:i:s'],
            'timezone' => ['required']
        ]);

        $exists = Statistic::where('user_id', Auth::id())->exists();
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

        if($date->isFuture()) {
            return back()->withErrors([
                'date_of_relapse' => 'The relapse date cannot be in the future.'
            ])->withInput();
        }

        Auth::user()->statistics()->create([ 'date_of_relapse' => $date, 'timezone' => $request->timezone ]);
        Auth::user()->relapseTracks()->create([ 'relapse_date' => $date ]);

        return back()->with('success', 'Successfully set the initial relapse date!');
    }

    public function setNewRelapse(Request $request) {
        $user = Auth::user();
        $lastRelapse = $user->relapseTracks()->latest('relapse_date')->first();
        $streakTime = abs(Carbon::now()->diffInSeconds($lastRelapse->relapse_date));
        // abs is needded because this expression returns the difference (now - last relapse date) so ensure a positive value
        
        $lastRelapse->update([ 'streak_time' => $streakTime ]);

        $date = now()->format('Y-m-d H:i:s');
    
        $user->relapseTracks()->create([ 'relapse_date' => $date ]);
        Auth::user()->statistics()->update([ 'date_of_relapse' => $date ]);

        return back()->with('success', 'Successfully updated the relapse date!');
    }

    public function resetRelapseData(Request $request) {
        RelapseTrack::where('user_id', Auth::id())->delete();
        Statistic::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Successfully reset relapse data');
    }

    public function userPosts(User $user) {
        $userPosts = $user->posts()->latest()->paginate(6);

        return view('users.posts', [ 'posts' => $userPosts, 'username' => $user->username ]);
    }

    public function getStatistics() {
        $data = Auth::user()->relapseTracks()->latest()->take(8)->get()->reverse();

        $data->each(function($item) {
            $item->relapse_date = Carbon::parse($item->relapse_date)->timezone(Auth::user()->statistics()->value('timezone'));
        });

        return response()->json($data);
    }
}
