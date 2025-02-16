<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Retrieves a list of comments for a post.
     */
    public function index()
    {
        //
    }

    /**
     * Adds a comment to a post.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Updates a comment.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Deletes a comment.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Likes a comment.
     */
    public function likeComment($id)
    {
        //
    }

    /**
     * Adds a reply to a comment.
     */
    public function replyToComment(Request $request, $id)
    {
        //
    }
}
