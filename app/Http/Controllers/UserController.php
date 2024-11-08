<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit(User $user) {
        Gate::authorize('modify', $user);
        
        return view('user.edit', ['user' => $user]);
    }
}
