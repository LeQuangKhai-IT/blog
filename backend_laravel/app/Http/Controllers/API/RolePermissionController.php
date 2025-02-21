<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Retrieve all roles.
     *
     * @return JsonResponse
     */
    public function indexRoles()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            return response()->json(['message' => 'No roles found'], 404);
        }

        return response()->json([
            'message' => 'Roles retrieved successfully',
            'data' => $roles
        ]);
    }

    /**
     * Create a new role.
     *
     * @param RolePermissionRequest $request
     * @return JsonResponse
     */
    public function storeRole(RolePermissionRequest $request)
    {
        $role = Role::create(['name' => $request->validated()['name']]);

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role
        ], 201);
    }

    /**
     * Update an existing role.
     *
     * @param RolePermissionRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function updateRole(RolePermissionRequest $request, Role $role)
    {
        $role->update($request->validated());

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroyRole(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 204);
    }

    /**
     * Retrieve all permissions.
     *
     * @return JsonResponse
     */
    public function indexPermissions()
    {
        $permissions = Permission::all();

        if ($permissions->isEmpty()) {
            return response()->json(['message' => 'No permissions found'], 404);
        }

        return response()->json([
            'message' => 'Permissions retrieved successfully',
            'data' => $permissions
        ]);
    }

    /**
     * Create a new permission.
     *
     * @param RolePermissionRequest $request
     * @return JsonResponse
     */
    public function storePermission(RolePermissionRequest $request)
    {
        $permission = Permission::create(['name' => $request->validated()['name']]);

        return response()->json([
            'message' => 'Permission created successfully',
            'data' => $permission
        ], 201);
    }

    /**
     * Update an existing permission.
     *
     * @param RolePermissionRequest $request
     * @param Permission $permission
     * @return JsonResponse
     */
    public function updatePermission(RolePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return response()->json([
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    /**
     * Delete a permission.
     *
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroyPermission(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully'], 204);
    }

    /**
     * Assign a permission to a role.
     *
     * @param RolePermissionRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function assignPermissionToRole(RolePermissionRequest $request, Role $role)
    {
        $permission = Permission::findByName($request->validated()['permission']);

        $role->givePermissionTo($permission);

        return response()->json([
            'message' => 'Permission assigned to role successfully',
            'data' => $role->permissions
        ]);
    }

    /**
     * Remove a permission from a role.
     *
     * @param Role $role
     * @param Permission $permission
     * @return JsonResponse
     */
    public function removePermissionFromRole(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);

        return response()->json([
            'message' => 'Permission removed from role successfully',
            'data' => $role->permissions
        ]);
    }
}
