<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Retrieves a list of all posts.
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json(PostResource::collection($posts));
    }

    /**
     * Creates a new post.
     *
     * @param  App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
        $post = Post::create($validatedData);

        return response()->json(PostResource::collection($post), 201);
    }

    /**
     * Retrieves details of a specific post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    /**
     * Updates a post.
     *
     * @param  App\Http\Requests\UpdatePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $post = Post::findOrFail($id);
        $post->update($validatedData);

        return response()->json($post);
    }

    /**
     * Deletes a post.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }

    /**
     * Retrieves a list of all posts published.
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function getPublished()
    {
        $posts = Post::where('published', true)->get();
        return response()->json($posts);
    }

    /**
     * Retrieves popular posts.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopular()
    {
        return response()->json(null, 204);
    }

    /**
     * Retrieves recent posts.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecent()
    {
        return response()->json(null, 204);
    }

    /**
     * Likes a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(string $id)
    {
        return response()->json(null, 204);
    }

    /**
     * Unlikes a post.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlikePost(string $id)
    {
        return response()->json(null, 204);
    }

    /**
     * Retrieves related posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPosts(string $id)
    {
        return response()->json(null, 204);
    }

    /**
     * Retrieves a post by its slug.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showBySlug(string $slug)
    {
        return response()->json(null, 204);
    }

    /**
     * Handles sharing a post on social media.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharePost(string $id)
    {
        return response()->json(null, 204);
    }

    /**
     * Searches for posts.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, $id)
    {
        return response()->json(null, 204);
    }

    /**
     * Request auth before like.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Post $post)
    {
        if (auth()->check()) {
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);

            return response()->json(['message' => 'Post liked!']);
        }

        return response()->json(['message' => 'You must be logged in to like.'], 403);
    }
}
