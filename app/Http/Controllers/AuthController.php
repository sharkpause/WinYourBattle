<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Events\UserLoggedIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'username' => ['required', 'max:30', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'image' => '/profile_images/default.jpeg',
            'bio' => 'This user has not set a bio yet 🤔'
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->intended('dashboard');
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required']
        ]);
        
        if(Auth::attempt($fields, $request->remember)) {
            event(new UserLoggedIn(Auth::user(), $request));
            
            return redirect()->intended('dashboard');
        } else {
            return back()->withErrors([
                'login_failed' => 'Either the email or the password was incorrect.'
            ]);
        }
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verifyNotice() { // Verify email page
        if(Auth::user()->email_verified_at !== null) return redirect('/dashboard');
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request) { // Verifies the email
        $request->fulfill();
         
        return redirect()->route('dashboard');
    }
    
    public function verifyHandler(Request $request) { // Resends verification email
        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('success', 'Verification link sent!');
    }

    public function verifyChangeEmail(Request $request) { // Changes the user email
        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users']
        ]);
        
        try {
            Auth::user()->update([ 'email' => $request->email ]);
        } catch(Exception $e) {
            return back()->withErrors([ 'message' => 'Something went wrong while updating your email, please try again later' ]);
        }

        return back()->with('success', 'Email changed successfully!');
    }
}
