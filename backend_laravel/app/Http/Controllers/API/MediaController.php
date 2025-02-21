<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the media files.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Media::all();

        if ($media->isEmpty()) {
            return response()->json(['message' => 'No media files found'], 404);
        }

        return response()->json([
            'message' => 'Media files retrieved successfully',
            'file' => response()->file(Storage::path($media->file_path))
        ]);
    }

    /**
     * Store a newly uploaded media file.
     *
     * @param  \App\Http\Requests\MediaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MediaRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('media');

        $media = Media::create([
            'user_id' => auth()->id(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
        ]);

        return response()->json([
            'message' => 'Media file uploaded successfully',
            'data' => $media
        ], 201);
    }

    /**
     * Display the specified media file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media file not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Media file retrieved successfully',
            'file' => response()->file(Storage::path($media->file_path))
        ]);
    }

    /**
     * Remove the specified media file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media file not found'
            ], 404);
        }

        Storage::delete($media->file_path);
        $media->delete();

        return response()->json([
            'message' => 'Media file deleted successfully'
        ], 204);
    }

    /**
     * Download the specified media file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media file not found'
            ], 404);
        }

        return response()->download(Storage::path($media->file_path));
    }
}
