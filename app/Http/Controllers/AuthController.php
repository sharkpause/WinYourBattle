<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'username' => ['required', 'max:30', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:3']
        ]);

        $user = User::create($fields);

        Auth::login($user);

        return redirect()->route('home');
    }
}
