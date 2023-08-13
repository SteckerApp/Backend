<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsController extends Controller
{
    use HandleResponse;

    public function getAllPermissions(Request $request,)
    {
        $permission = Permission::all();
        return $this->successResponse($permission, 'Permissions retrieved successfully!', 200);
    }

    public function getAllRoles(Request $request,)
    {
        $roles = Role::with('permissions')->get();
        return $this->successResponse($roles, 'Roles retrieved successfully!', 200);
    }

    public function createRole(Request $request,)
    {
        $role = Role::create(['name' => $request->input('role_name'),'guard_name' => 'sanctum']);


        $permissionIds = $request->input('permission_ids'); 

        $permissions = Permission::whereIn('id', $permissionIds)->get();

        $role->syncPermissions($permissions);

        return $this->successResponse($role, 'Roles created successfully!', 201);
    }

    public function editRole(Request $request, $role_id)
    {
        $role = Role::find($role_id);

        if (!$role) {
            // Handle role not found error
            return $this->errorResponse(false, 'Rolenot found!', 404);
        }
        $role->name = $request->role_name;
        $role->save();

        return $this->successResponse($role, 'Roles updated successfully!', 201);
    }

    public function deleteRole(Request $request, $role_id)
    {
        $role = Role::find($role_id);

        if (!$role) {
            // Handle role not found error
            return $this->errorResponse(false, 'Rolenot found!', 404);
        }

        $role->delete();

        return $this->successResponse($role, 'Roles deleted successfully!', 201);
    }
}
