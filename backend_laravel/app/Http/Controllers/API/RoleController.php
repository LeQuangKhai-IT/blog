<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Retrieves a list of roles.
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    /**
     * Creates a new role.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Updates a role.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Deletes a role.
     */
    public function destroy(string $id)
    {
        //
    }
}
