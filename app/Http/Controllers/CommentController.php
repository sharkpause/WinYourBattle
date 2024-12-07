<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    public function show(Request $request, $post_id)
    {
        $comments = Comment::where('post_id', $post_id)->latest()->paginate(10);
        return response()->json([
            'html' => view('components.commentSection', [ 'comments' => $comments, 'post_id' => $post_id ])->render(),
            'paginator' => view('components.paginator', [ 'items' => $comments ])->render()
        ]);
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

    private function validateUserAndComment($userID, $commentID) {
        if(Validator::make(
            ['user_id' => $userID, 'comment_id' => $commentID],
            [
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'comment_id' => ['required', 'integer', 'exists:comments,id'],
            ]
        )->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if(!(User::where('id', $userID)->exists() && Comment::where('id', $commentID)->exists())) {
            return response()->json(['error' => 'Invalid user or comment'], 404);
        }

        return true;
    }
}
