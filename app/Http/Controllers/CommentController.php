<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => 'required|max:255',
        ]);

        $post->comments()->create([
            'comment' => $data['comment'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id || auth()->id() !== $comment->post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId);
    }
}
