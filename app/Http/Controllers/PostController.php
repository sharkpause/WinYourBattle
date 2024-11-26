<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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

    public function like(Request $request, $id) { // $id = post ID
        if(!$this->validateUserAndPost(Auth::user()->id, $id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);
        
        if(Dislike::where('user_id', Auth::user()->id)->where('post_id', $id)->exists()) {
            $this->handleUndislike($id);
        }

        Like::create([
            'user_id' => Auth::user()->id,
            'post_id' => $id,
        ]);

        $post = Post::findOrFail($id);
        $post->increment('like_count');

        return response()->json([
            'like_count' => $post->like_count
        ]);
    }

    public function unlike(Request $request, $id) { // $id = post ID
        if(!$this->validateUserAndPost(Auth::user()->id, $id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);

        $this->handleUnlike($id);
        
        return response()->json([
            'like_count' => $post->like_count
        ]);
    }

    public function dislike(Request $request, $id) { // $id = post ID
        if(!$this->validateUserAndPost(Auth::user()->id, $id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);

        if(Like::where('user_id', Auth::user()->id)->where('post_id', $id)->exists()) {
            $this->handleUnlike($id);
        }

        Dislike::create([
            'user_id' => Auth::user()->id,
            'post_id' => $id,
        ]);

        $post = Post::findOrFail($id);
        $post->increment('dislike_count');

        return response()->json([
            'dislike_count' => $post->dislike_count
        ]);
    }

    public function undislike(Request $request, $id) { // $id = post ID
        if(!$this->validateUserAndPost(Auth::user()->id, $id))
            return response()->json([ 'error' => "User or post doesn't exist" ], 404);

        $this->handleUndislike($id);
        
        return response()->json([
            'dislike_count' => $post->dislike_count
        ]);
    }

    private function validateUserAndPost($userID, $postID) {
        return User::where('id', $userID)->exists() && Post::where('id', $postID)->exists();
    }

    private function handleUndislike($id) {
        $dislike = Dislike::where('user_id', Auth::user()->id)->where('post_id', $id)->firstOrFail();

        $dislike->delete();
        
        $post = Post::findOrFail($id);
        $post->decrement('dislike_count');
    }

    private function handleUnlike($id) {
        $like = Like::where('user_id', Auth::user()->id)->where('post_id', $id)->firstOrFail();

        $like->delete();
        
        $post = Post::findOrFail($id);
        $post->decrement('like_count');
    }
}
