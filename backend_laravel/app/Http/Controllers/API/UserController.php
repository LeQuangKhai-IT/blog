<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Retrieves a list of all users.
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found',
            ], 404);
        }

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users,
        ]);
    }

    /**
     * Retrieves details of a specific user.
     * @param String $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user,
        ]);
    }

    /**
     * Registers a new user.
     *
     * @param  App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {

        $validated = $request->validated();
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Updates user information.
     *
     * @param  App\Http\Requests\UpdateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $validated = $request->validated();
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ]);
    }

    /**
     * Deletes a user.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Retrieves posts by a user.
     */
    public function getPostsByUser(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $posts = $user->posts;

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => $posts,
        ]);
    }

    /**
     * Retrieves comments by a user.
     */
    public function getCommentsByUser(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $comments = $user->comments;

        return response()->json([
            'message' => 'Comments retrieved successfully',
            'data' => $comments,
        ]);
    }

    /**
     * Follows a user.
     */
    public function followUser(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $authUser = User::find(auth()->user());
        $authUser->follows()->attach($user->id);

        return response()->json([
            'message' => 'User followed successfully',
        ]);
    }

    /**
     * Unfollows a user.
     */
    public function unfollowUser(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $authUser = User::find(auth()->user());
        $authUser->follows()->detach($user->id);

        return response()->json([
            'message' => 'User unfollowed successfully',
        ]);
    }
}
