<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Following;
use Illuminate\Http\Request;
use App\Models\FollowRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request, $user_id) {
        $user = User::findOrFail($user_id);

        if(
            $user->public === 1 ||
            ($user->public === 0 && Following::where('user_id', Auth::id())->where('following_id', $user_id)->first()) ||
            Auth::id() == $user_id
        ) {
            return view('users.profile', [
                'posts' => $user->posts()->paginate(10),
                'user' => $user,
                'private' => false,
            ]);
        } else {
            return view('users.profile', [
                'private' => true,
                'user' => $user
            ]);
        }
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
            'image' => ['nullable', 'file', 'max:4096', 'mimes:png,jpg,jpeg,webp'],
            'public' => ['nullable']
        ]);

        $newBio = 'No bio';
        $newImage = '/profile_images/default.jpeg';
        $newPublic = 1;

        if($request->has('bio') && $request->bio != '') {
            $newBio = $request->bio;
        }
        if($request->has('image')) {
            $newImage = Storage::disk('public')->put('profile_images', $request->image);
        }
        if($request->has('public')) {
            if($request->public === 'on') // public on = private, off = public
                $newPublic = 0;
        }

        $user->update([
            'bio' => $newBio,
            'image' => $newImage,
            'public' => $newPublic
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
        
        if(User::findOrFail($user_id)->public === 0) {
            FollowRequest::create([
                'follower_id' => Auth::id(),
                'followed_id' => $user_id
            ]);

            return response()->json([ 'message' => 'Successfully requested a follow' ], 200);
        }

        Following::create([
            'user_id' => Auth::id(),
            'following_id' => $user_id
        ]);

        return response()->json([
            'followerCount' => Following::where('following_id', $user_id)->count(),
            'followingCount' => Following::where('user_id', $user_id)->count()
        ]);
    }

    public function unfollow(Request $request, $user_id) {
        if(Auth::id() === $user_id)
            return response()->json([ 'error' => "You can't unfollow yourself" ]);

        $followRequest = FollowRequest::where('follower_id', Auth::id())->where('followed_id', $user_id );
        if(User::findOrFail($user_id)->public === 0 && $followRequest) {
            $followRequest->delete();

            return response()->json([ 'message' => 'Successfully unrequested a follow' ], 200);
        }

        $following = Following::where('user_id', Auth::id())->where('following_id', $user_id);
        if(!$following->exists())
            return response()->json([ 'error' => 'You are already unfollowing this person' ], 400);

        $following->delete();

        return response()->json([ 'followCount' => Following::where('following_id', $user_id)->count() ]);
    }

    public function getFollowers(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        $followers = User::whereIn('id', $user->followers()->pluck('user_id'))
                        ->select(['id', 'image', 'username', 'public'])
                        ->paginate(50)
                        ->through(function ($follower) {
                            $follower->image_url = asset('storage' . $follower->image);
                            $follower->followURL = route('users.follow', $follower->id);
                            $follower->unfollowURL = route('users.unfollow', $follower->id);
                            $follower->profileURL = route('profile', $follower->id);
                            $follower->followedByAuth =
                                Following::where('user_id', Auth::id())->where('following_id', $follower->id)->exists()
                                ? true : false;
                            $follower->requestedByAuth =
                                FollowRequest::where('follower_id', Auth::id())->where('followed_id', $follower->id)->exists()
                                ? true : false;
                            $follower->private = !$follower->public;

                            return $follower;
                        });

        return response()->json([
            'followers' => $followers
        ], 200);
    }

    public function getFollowings(Request $request, $user_id) {
        $user = User::findOrFail($user_id);
        $followings = User::whereIn('id', $user->followings()->pluck('following_id'))
                        ->select(['id', 'image', 'username'])
                        ->paginate(50)
                        ->through(function ($following) {
                            $following->image_url = asset('storage' . $following->image);
                            $following->followURL = route('users.follow', $following->id);
                            $following->unfollowURL = route('users.unfollow', $following->id);
                            $following->profileURL = route('profile', $following->id);
                            $following->followedByAuth =
                                Following::where('user_id', Auth::id())->where('following_id', $following->id)->exists()
                                ? true : false;
                            $following->requestedByAuth =
                                FollowRequest::where('follower_id', Auth::id())->where('followed_id', $following->id)->exists()
                                ? true : false;
                            $following->private = !$following->public;

                            return $following;
                        });

        return response()->json([
            'followings' => $followings
        ], 200);
    }

    public function getFollowRequests(Request $request) {
        $followRequests = FollowRequest::where('followed_id', Auth::id())
                            ->select(['follower_id'])
                            ->paginate(50)
                            ->through(function ($followRequest) {
                                $account = User::findOrFail($followRequest->follower_id);
                                $followRequest->image_url = asset('storage' . $account->image);
                                $followRequest->username = $account->username;
                                $followRequest->acceptURL = route('follow-request', [Auth::id(), $followRequest->follower_id]);
                                $followRequest->rejectURL = route('follow-request', [Auth::id(), $followRequest->follower_id]);
                            
                                return $followRequest;
                            });
        return response()->json($followRequests, 200);
    }

    public function acceptFollowRequest(Request $request) {
        // Get follower ID
        // Add follower ID to followings table
        // Delete follower request with follower ID
    }

    public function rejectFollowRequest(Request $request) {
        //$request->validate([
        //    ''
        //]);
        // Delete follower request with follower ID
    }
}
