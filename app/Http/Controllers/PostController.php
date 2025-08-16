<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Models\Following;
use App\Models\PostDislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware() : array {
        return [
            new Middleware(['auth', 'verified'], except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = null;

        if(Auth::check()) {
            $user = Auth::user();

            $followingIds = Following::where('user_id', $user->id)->pluck('following_id');

            // Fetch posts where author is public OR is followed by auth user
            $posts = Post::whereIn('user_id', $followingIds) // posts from followed users
                        ->orWhereHas('user', function($query) {
                            $query->where('public', 1); // posts from public users
                        })
                            ->latest()
                            ->paginate(10);
        } else {
            $posts = Post::whereHas('user', function($query) {
                $query->where('public', 1);
            })
                ->latest()
                ->paginate(10);
        }

        return view('posts.index', [ 'posts' => $posts ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:4096', 'mimes:png,jpg,jpeg,webp']
        ]);

        $path = null;
        $filename = null;
        if($request->hasFile('image')) {
            $filename = uniqid('img_', true) . '.' . $request->image->getClientOriginalExtension();
            
            if(Auth::user()->public) {
                Storage::disk('gcs_public')->put("posts_images/{$filename}", file_get_contents($request->image));
                $path = Storage::disk('gcs_public')->url("posts_images/{$filename}");
            } else {
                Storage::disk('gcs_private')
                    ->put("posts_images/{$filename}", file_get_contents($request->image));
                $path = "posts_images/{$filename}";
            }
        }

        Auth::user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path,
            'like_count' => 0,
            'dislike_count' => 0
        ]);

        return redirect()->route('posts.index')->with([ 'success' => 'Your post was posted!' ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', [ 'post' => $post ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('modify', $post);

        return view('posts.edit', [ 'post' => $post ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);

        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:4096', 'mimes:png,jpg,jpeg,webp']
        ]);

        $path = $post->image ?? null;
        if($request->hasFile('image')) {
            if($post->image) {
                Storage::disk('gcs_public')->delete($post->image);
            }
            $path = Storage::disk('gcs_public')->put('posts_images', $request->image);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => asset('storage/' . $path),
        ]);

        return redirect()->route('posts.index')->with([ 'success' => 'Your post was updated!' ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if(!(Auth::id() === $post->user_id))
            return back()->withErrors([ 'error' => 'You are not authorized to delete this post' ]);

        if($post->image) {
            Storage::disk('gcs_public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Your post was deleted!');
    }

    public function like(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::id(), $post_id);
        
        if(PostDislike::where('user_id', Auth::id())->where('post_id', $post_id)->exists()) {
            $this->handleUndislike($post_id);
        }

        PostLike::create([
            'user_id' => Auth::id(),
            'post_id' => $post_id,
        ]);

        $post = Post::findOrFail($post_id);
        $post->increment('like_count');

        return response()->json([
            'like_count' => $post->like_count
        ], 200);
    }

    public function unlike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::id(), $post_id);
        if(!PostLike::where('user_id', Auth::id())->where('post_id', $post_id)->exists())
            return response()->json([ 'error' => 'You cannot unlike a post you already did not like' ]);

        $this->handleUnlike($post_id);
        
        return response()->json([
            'like_count' => Post::findOrFail($post_id)->like_count
        ], 200);
    }

    public function dislike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::id(), $post_id);

        if(!$this->validateUserAndPost(Auth::id(), $post_id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);

        if(PostLike::where('user_id', Auth::id())->where('post_id', $post_id)->exists()) {
            $this->handleUnlike($post_id);
        }

        PostDislike::create([
            'user_id' => Auth::id(),
            'post_id' => $post_id,
        ]);

        $post = Post::findOrFail($post_id);
        $post->increment('dislike_count');

        return response()->json([
            'dislike_count' => $post->dislike_count
        ], 200);
    }

    public function undislike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::id(), $post_id);
        if(!PostDislike::where('user_id', Auth::id())->where('post_id', $post_id)->exists())
            return response()->json([ 'error' => 'You cannot undislike a post you already did not dislike' ]);

        $this->handleUndislike($post_id);
        
        return response()->json([
            'dislike_count' => Post::findOrFail($post_id)->dislike_count
        ], 200);
    }

    public function following(Request $request) {
        if(!Following::where('user_id', Auth::id())->exists())
            return view('posts.following', [
                'posts' => new LengthAwarePaginator(
                    collect([]), // Empty collection
                    0,           // Total number of items
                    10           // Items per page
                )]);

        $posts = Post::whereIn('user_id',
            Auth::user()->followings()->pluck('following_id') // ID of all of user's follows
        )->latest()->paginate(10);

        return view('posts.following', [ 'posts' => $posts ]);
    }

    private function validateUserAndPost($userID, $postID) {
        if(Validator::make(
            [ 'user_id' => $userID, 'post_id' => $postID ],
            [
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'post_id' => ['required', 'integer', 'exists:posts,id'],
            ]
        )->fails()) {
            return response()->json([ 'errors' => $validator->errors() ], 422);
        }
        
        if(!(User::where('id', $userID)->exists() && Post::where('id', $postID)->exists())) {
            return response()->json([ 'error' => 'Invalid user or post' ], 404);
        }

        return true;
    }

    private function handleUndislike($post_id) {
        $dislike = PostDislike::where('user_id', Auth::id())->where('post_id', $post_id)->firstOrFail();

        $dislike->delete();
        
        $post = Post::findOrFail($post_id);
        $post->decrement('dislike_count');
    }

    private function handleUnlike($post_id) {
        $like = PostLike::where('user_id', Auth::id())->where('post_id', $post_id)->firstOrFail();

        $like->delete();
        
        $post = Post::findOrFail($post_id);
        $post->decrement('like_count');
    }
}
