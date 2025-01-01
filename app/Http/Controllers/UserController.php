<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Following;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        return view('users.profile', [
            'posts' => $user->posts()->paginate(10),
            'user' => $user
        ]);
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

        return redirect()->route('profile', $user_id)->with('success', 'Your account was successfully updated!');
    }

    public function delete(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        Gate::authorize('delete', [$user, $user_id]);

        $user->delete();
        
        return redirect()->route('login')->with('success', 'Your account was successfully deleted!');
    }

    public function follow(Request $request, $user_id) {
        if(Auth::id() === $user_id)
            return response()->json([ 'error' => "You can't follow yourself" ]);
        if(Following::where('user_id', Auth::id())->where('following_id', $user_id)->exists())
            return response()->json([ 'error' => 'You are already following this person' ], 400);

        Following::create([
            'user_id' => Auth::id(),
            'following_id' => $user_id
        ]);

        return response()->json([ 'success' => 'Sucessfully followed user with ID ' . $user_id ]);
    }

    public function unfollow(Request $request, $user_id) {
        if(Auth::id() === $user_id)
            return response()->json([ 'error' => "You can't unfollow yourself" ]);

        $following = Following::where('user_id', Auth::id())->where('following_id', $user_id);
        if(!$following->exists())
            return response()->json([ 'error' => 'You are already unfollowing this person' ], 400);

        $following->delete();

        return response()->json([ 'success' => 'Successfully unfollowed user with ID' . $user_id ]);
    }
}
