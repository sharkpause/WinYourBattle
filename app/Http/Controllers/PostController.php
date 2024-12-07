<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Models\PostDislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
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
        $posts = Post::latest()->paginate(10);

        return view('posts.index', ['posts' => $posts]);
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
        if($request->hasFile('image')) {
            $path = Storage::disk('public')->put('posts_images', $request->image);
        }

        Auth::user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path,
            'like_count' => 0,
            'dislike_count' => 0
        ]);

        return redirect()->route('posts.index')->with(['success' => 'Your post was posted!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('modify', $post);

        return view('posts.edit', ['post' => $post]);
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
                Storage::disk('public')->delete($post->image);
            }
            $path = Storage::disk('public')->put('posts_images', $request->image);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path,
        ]);

        return redirect()->route('posts.index')->with(['success' => 'Your post was updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', Auth::user()->id, $post->id);

        if($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Your post was deleted!');
    }

    public function like(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::user()->id, $post_id);
        if($validatorResponse != true)
            return $validatorResponse;
        
        if(PostDislike::where('user_id', Auth::user()->id)->where('post_id', $post_id)->exists()) {
            $this->handleUndislike($post_id);
        }

        PostLike::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
        ]);

        $post = Post::findOrFail($post_id);
        $post->increment('like_count');

        return response()->json([
            'like_count' => $post->like_count
        ]);
    }

    public function unlike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::user()->id, $post_id);
        if($validatorResponse != true)
            return $validatorResponse;

        $this->handleUnlike($post_id);
        
        return response()->json([
            'like_count' => Post::findOrFail($post_id)->like_count
        ]);
    }

    public function dislike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::user()->id, $post_id);
        if($validatorResponse != true)
            return $validatorResponse;

        if(!$this->validateUserAndPost(Auth::user()->id, $post_id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);

        if(PostLike::where('user_id', Auth::user()->id)->where('post_id', $post_id)->exists()) {
            $this->handleUnlike($post_id);
        }

        PostDislike::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post_id,
        ]);

        $post = Post::findOrFail($post_id);
        $post->increment('dislike_count');

        return response()->json([
            'dislike_count' => $post->dislike_count
        ]);
    }

    public function undislike(Request $request, $post_id) {
        $validatorResponse = $this->validateUserAndPost(Auth::user()->id, $post_id);
        if($validatorResponse != true)
            return $validatorResponse;

        $this->handleUndislike($post_id);
        
        return response()->json([
            'dislike_count' => Post::findOrFail($post_id)->dislike_count
        ]);
    }

    private function validateUserAndPost($userID, $postID) {
        if(Validator::make(
            ['user_id' => $userID, 'post_id' => $postID],
            [
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'post_id' => ['required', 'integer', 'exists:posts,id'],
            ]
        )->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        if(!(User::where('id', $userID)->exists() && Post::where('id', $postID)->exists())) {
            return response()->json(['error' => 'Invalid user or post'], 404);
        }

        return true;
    }

    private function handleUndislike($post_id) {
        $dislike = PostDislike::where('user_id', Auth::user()->id)->where('post_id', $post_id)->firstOrFail();

        $dislike->delete();
        
        $post = Post::findOrFail($post_id);
        $post->decrement('dislike_count');
    }

    private function handleUnlike($post_id) {
        $like = PostLike::where('user_id', Auth::user()->id)->where('post_id', $post_id)->firstOrFail();

        $like->delete();
        
        $post = Post::findOrFail($post_id);
        $post->decrement('like_count');
    }

}
