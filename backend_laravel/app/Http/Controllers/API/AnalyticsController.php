<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnalyticsRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    /**
     * Retrieve post analytics (e.g., views, likes).
     *
     * @param AnalyticsRequest $request
     * @return JsonResponse
     */
    public function postAnalytics(AnalyticsRequest $request)
    {
        $posts = Post::with('likes', 'views')->get();

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found'], 404);
        }

        return response()->json([
            'message' => 'Post analytics retrieved successfully',
            'data' => $posts
        ]);
    }

    /**
     * Retrieve user analytics (e.g., registered users, activity).
     *
     * @param AnalyticsRequest $request
     * @return JsonResponse
     */
    public function userAnalytics(AnalyticsRequest $request)
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json([
            'message' => 'User analytics retrieved successfully',
            'data' => $users
        ]);
    }

    /**
     * Retrieve comment analytics (e.g., activity).
     *
     * @param AnalyticsRequest $request
     * @return JsonResponse
     */
    public function commentAnalytics(AnalyticsRequest $request)
    {
        $comments = Comment::all();

        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments found'], 404);
        }

        return response()->json([
            'message' => 'Comment analytics retrieved successfully',
            'data' => $comments
        ]);
    }

    /**
     * Retrieve tag analytics (e.g., post counts for each tag).
     *
     * @param AnalyticsRequest $request
     * @return JsonResponse
     */
    public function tagAnalytics(AnalyticsRequest $request)
    {
        $tags = Tag::withCount('posts')->get();

        if ($tags->isEmpty()) {
            return response()->json(['message' => 'No tags found'], 404);
        }

        return response()->json([
            'message' => 'Tag analytics retrieved successfully',
            'data' => $tags
        ]);
    }

    /**
     * Retrieve traffic analytics.
     *
     * @param AnalyticsRequest $request
     * @return JsonResponse
     */
    public function trafficAnalytics(AnalyticsRequest $request)
    {
        // Placeholder for traffic analytics logic
        $traffic = []; // You will populate this based on your traffic data

        if (empty($traffic)) {
            return response()->json(['message' => 'No traffic data found'], 404);
        }

        return response()->json([
            'message' => 'Traffic analytics retrieved successfully',
            'data' => $traffic
        ]);
    }
}
