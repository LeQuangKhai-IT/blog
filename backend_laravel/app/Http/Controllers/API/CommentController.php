<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyToCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Retrieve a list of comments for a post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id)
    {
        $comments = Comment::where('post_id', $id)->get();
        return response()->json($comments);
    }

    /**
     * Add a comment to a post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id  The ID of the post to add a comment to.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'post_id' => $id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment,
        ]);
    }

    /**
     * Update a comment.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $validated = $request->validate();
        $comment->update($validated);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ]);
    }

    /**
     * Delete a comment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    /**
     * Like a comment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->likes()->attach(auth()->id());

        return response()->json([
            'message' => 'Comment liked successfully',
        ]);
    }

    /**
     * Reply to a comment.
     *
     * @param  \App\Http\Requests\ReplyToCommentRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function replyToComment(ReplyToCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $validated = $request->validate();

        $reply = $comment->replies()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'data' => $reply,
        ]);
    }
}
