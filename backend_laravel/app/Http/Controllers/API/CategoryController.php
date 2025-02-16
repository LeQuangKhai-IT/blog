<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Retrieves a list of all categories.
     */
    public function index()
    {
        //
    }

    /**
     * Creates a new category.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Retrieves details of a specific category.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Updates a category.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Deletes a category.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Retrieves posts under a category.
     */
    public function getPostsByCategory(string $id)
    {
        //
    }
}
