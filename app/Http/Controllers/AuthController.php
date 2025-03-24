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
use Laravel\Socialite\Facades\Socialite;
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
            'image' => asset('storage/profile_images/default.jpeg'),
            'bio' => 'This user has not set a bio yet 🤔'
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->route('verification.notice');
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

    public function googleRedirect(Request $request) {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request) {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrNew(['email' => $googleUser->email]);
        
        if ($user->exists) {
            $user->google_id = $user->google_id ?? $googleUser->id;
            $user->image = ($user->image === asset('storage/profile_images/default.jpeg')) 
                            ? $googleUser->avatar 
                            : $user->image;
            $user->email_verified_at = $user->email_verified_at ?? now();
        } 
    
        else {
            $user->username = $googleUser->name;
            $user->password = bcrypt(str()->random(12));
            $user->bio = 'This user has not set a bio yet 🤔';
            $user->google_id = $googleUser->id;
            $user->image = $googleUser->avatar;
            $user->email_verified_at = now();
        }
    
        $user->save();
        Auth::login($user);
    
        return redirect('/dashboard');
    }

    public function githubRedirect(Request $request) {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback(Request $request) {
        $githubUser = Socialite::driver('github')->user();

        $user = User::firstOrNew(['email' => $githubUser->email]);
        
        if ($user->exists) {
            $user->github_id = $user->github_id ?? $githubUser->id;
            $user->image = ($user->image === asset('storage/profile_images/default.jpeg')) 
                            ? $githubUser->avatar 
                            : $user->image;
            $user->email_verified_at = $user->email_verified_at ?? now();
        } 
    
        else {
            $user->username = $githubUser->name;
            $user->password = bcrypt(str()->random(12));
            $user->bio = 'This user has not set a bio yet 🤔';
            $user->github_id = $githubUser->id;
            $user->image = $githubUser->avatar;
            $user->email_verified_at = now();
        }
    
        $user->save();
        Auth::login($user);
    
        return redirect('/dashboard');
    }
}
