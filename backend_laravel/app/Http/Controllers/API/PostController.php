<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Retrieve a list of all posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::all();

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No posts found',
            ], 404);
        }

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Retrieve the details of a specific post.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Post retrieved successfully',
            'data' => $post,
        ]);
    }

    /**
     * Create a new post.
     *
     * @param \App\Http\Requests\StorePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    /**
     * Update a post.
     *
     * @param \App\Http\Requests\UpdatePostRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->update($request->validated());
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ]);
    }

    /**
     * Delete a post.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Retrieve a list of all published posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublished()
    {
        $posts = Post::where('is_published', true)->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'No published posts found',
            ], 404);
        }

        return response()->json([
            'message' => 'Published posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Retrieve a list of the most popular posts (based on views).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopular()
    {
        $posts = Post::orderBy('views', 'desc')->limit(10)->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No posts found by views',
            ], 404);
        }

        return response()->json([
            'message' => 'Posts retrieved by views successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Retrieve a list of the most recent posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecent()
    {
        $posts = Post::latest()->limit(5)->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No recent posts found',
            ], 404);
        }

        return response()->json([
            'message' => 'Top 5 latest posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Like a post.
     *
     * @param string id
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->likes()->attach(auth()->id());

        return response()->json([
            'message' => 'Post liked successfully',
        ], 200);
    }

    /**
     * Unlike a post.
     *
     * @param string id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlikePost(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->likes()->detach(auth()->id());

        return response()->json([
            'message' => 'Post unliked successfully',
        ]);
    }

    /**
     * Retrieve related posts.
     *
     * @param string id
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPosts(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->get();

        return response()->json([
            'message' => 'Related posts retrieved successfully',
            'data' => $relatedPosts,
        ]);
    }

    /**
     * Retrieve a post by its slug.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function showBySlug($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return response()->json([
            'message' => 'Post retrieved successfully',
            'data' => $post,
        ]);
    }

    /**
     * Share a post on social media.
     *
     * @param string id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharePost(string $id)
    {
        // Logic for sharing on social media goes here
        return response()->json(['message' => 'Post shared successfully']);
    }

    /**
     * Search for posts by keyword.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->query('keyword');

        if (!$query) {
            return response()->json([
                'message' => 'No search keyword provided',
            ], 400);
        }

        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No posts found for the given keyword',
            ], 404);
        }

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => $posts,
        ]);
    }
}
