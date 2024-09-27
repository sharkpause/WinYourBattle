<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public function index(Request $request) {
        return view('users.dashboard');
    }
}
