<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\TagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Retrieve a list of all tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tags = Tag::all();

        if ($tags->isEmpty()) {
            return response()->json([
                'message' => 'No tags found',
            ], 404);
        }

        return response()->json([
            'message' => 'Tags retrieved successfully',
            'data' => $tags,
        ]);
    }

    /**
     * Retrieve the details of a specific tag.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Tag retrieved successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Create a new tag.
     *
     * @param  \App\Http\Requests\StoreTagRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();
        $tag = Tag::create($validated);

        return response()->json([
            'message' => 'Tag created successfully',
            'data' => $tag,
        ], 201);
    }

    /**
     * Update an existing tag.
     *
     * @param  \App\Http\Requests\UpdateTagRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        $validated = $request->validated();

        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found',
            ], 404);
        }

        $tag->update($validated);

        return response()->json([
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Delete a tag.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found',
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
