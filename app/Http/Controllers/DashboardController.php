<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        return view('users.dashboard');
    }

    public function setRelapseDate(Request $request) {
        $fields = $request->validate([
            'relapseDate' => ['required', 'date']
        ]);

        return redirect('dashboard');
    }
}
