<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function passwordEmail(Request $request) {
        $request->validate([ 'email' => ['required', 'email'] ]);
     
        $status = Password::sendResetLink($request->only('email'));
     
        return $status === Password::RESET_LINK_SENT // Checks if the reset link was successfully sent
                    ? back()->with([ 'status' => __($status) ])
                    : back()->withErrors([ 'email' => __($status) ]);
    }

    public function passwordReset(string $token) {
        return view('auth.reset-password', [ 'token' => $token ]);
    }

    public function passwordUpdate(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([ // Hashes the password
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET // Checks if the password reset was successful or not
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors([ 'email' => [__($status)] ]);
    }
}
