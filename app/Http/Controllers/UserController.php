<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function edit(User $user, $id) {
        Gate::authorize('update', [User::findOrFail($id), $id]);
        
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        Gate::authorize('update', [$user, $id]);

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

        return redirect()->route('dashboard')->with(['success' => 'Your account was updated!']);
    }
}
