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
        $comments = Comment::latest()->paginate(10);

        return response()->json([ 'comments' => $comments ]);
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
        
        $comment = Comment::create([
            'post_id' => $post_id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'like_count' => 0,
            'dislike_count' => 0
        ]);

        return response()->json([ 'html' => view('components.commentCard', ['comment' => $comment])->render() ]);
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
