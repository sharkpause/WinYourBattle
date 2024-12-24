<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request, $user_id) {
        return view('users.profile', [ 'user' => User::findOrFail($user_id), 'posts' => $user->posts()->paginate(10) ]);
    }

    public function edit(User $user, $user_id) {
        Gate::authorize('update', [User::findOrFail($user_id), $user_id]);
        
        return view('users.edit', [ 'user' => $user ]);
    }

    public function update(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        Gate::authorize('update', [$user, $user_id]);

        $request->validate([
            'bio' => ['nullable', 'max:255'],
            'image' => ['nullable', 'file', 'max:4096', 'mimes:png,jpg,jpeg,webp']
        ]);

        $newBio = 'No bio';
        $newImage = '/profile_images/default.jpeg';

        if($request->has('bio') && $request->bio != '') {
            $newBio = $request->bio;
        }
        if($request->has('image')) {
            $newImage = Storage::disk('public')->put('profile_images', $request->image);
        }

        $user->update([
            'bio' => $newBio,
            'image' => $newImage
        ]);

        return redirect()->route('profile')->with('success', 'Your account was successfully updated!');
    }

    public function delete(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        Gate::authorize('delete', [$user, $user_id]);

        $user->delete();
        
        return redirect()->route('login')->with('success', 'Your account was successfully deleted!');
    }
}
