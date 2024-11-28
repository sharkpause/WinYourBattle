<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        if(!Post::where('id', $post_id)->exists())
            return response()->json([ 'error' => 'Post does not exist'], 404);

        $fields = $request->validate([
            'body' => ['required'],
        ]);
        
        Auth::user()->comments()->create([
            'body' => $request->body,
            'like_count' => 0,
            'dislike_count' => 0,
            'user_id' => Auth::user()->id,
            'post_id' => $post_id
        ]);

        return response()->json([ 'success' => 'Your comment was posted!' ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
