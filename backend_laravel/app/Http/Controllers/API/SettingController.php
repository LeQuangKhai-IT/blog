<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;

class SettingController extends Controller
{
    /**
     * Retrieve blog configuration settings (e.g., title, description, logo).
     *
     * @return JsonResponse
     */
    public function index()
    {
        $settings = config('blog_settings');

        if (empty($settings)) {
            return response()->json(['message' => 'No settings found'], 404);
        }

        return response()->json([
            'message' => 'Settings retrieved successfully',
            'data' => $settings
        ]);
    }

    /**
     * Update blog configuration settings.
     *
     * @param SettingRequest $request
     * @return JsonResponse
     */
    public function update(SettingRequest $request)
    {
        $data = $request->validated();
        // Assuming you have a logic to update settings, e.g., saving to a file or database

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => $data
        ]);
    }

    /**
     * Retrieve information about the blog theme.
     *
     * @return JsonResponse
     */
    public function getTheme()
    {
        $theme = config('blog_theme');

        if (empty($theme)) {
            return response()->json(['message' => 'No theme information found'], 404);
        }

        return response()->json([
            'message' => 'Theme information retrieved successfully',
            'data' => $theme
        ]);
    }

    /**
     * Update the blog's theme.
     *
     * @param SettingRequest $request
     * @return JsonResponse
     */
    public function updateTheme(SettingRequest $request)
    {
        $data = $request->validated();
        // Assuming you have a logic to update the theme, e.g., saving to a file or database

        return response()->json([
            'message' => 'Theme updated successfully',
            'data' => $data
        ]);
    }
}
