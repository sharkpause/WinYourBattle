<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function edit(User $user, $id) {
        Gate::authorize('update', [User::findOrFail($id), $id]);
        
        return view('users.edit', ['user' => $user]);
    }
}
