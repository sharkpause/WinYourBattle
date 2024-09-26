<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function setTimeZone(Request $request) {
        $userTimezone = $request->timezone;

        $currentTime = Carbon::now($userTimezone)->format('H');

        return response()->json([ 'current_time' => $currentTime ]);
    }

    public function index(Request $request) {
        return view('users.dashboard');
    }
}
