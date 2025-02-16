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

        return response()->json(UserResource::collection($users));
    }

    /**
     * Registers a new user.
     *
     * @param  App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {

        $validatedData = $request->validated();
        $user = User::create($validatedData);

        return response()->json(UserResource::collection($user), 201);
    }

    /**
     * Retrieves details of a specific user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Updates user information.
     *
     * @param  App\Http\Requests\UpdateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Deletes a user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Retrieves posts by a user.
     */
    public function getPostsByUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Retrieves comments by a user.
     */
    public function getCommentsByUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Follows a user.
     */
    public function followUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Unfollows a user.
     */
    public function unfollowUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
