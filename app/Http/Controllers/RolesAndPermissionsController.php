<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\PermissionGroup;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsController extends Controller
{
    use HandleResponse;

    public function getAllPermissions(Request $request,)
    {
        $permission = PermissionGroup::with('permissions')->get();
        return $this->successResponse($permission, 'Permissions retrieved successfully!', 200);
    }

    public function getAllRoles(Request $request,)
    {
        $roles = Role::with('permissions')->whereNot('name', 'client')->get();
        return $this->successResponse($roles, 'Roles retrieved successfully!', 200);
    }

    public function getUserRoles(Request $request,)
    {
        $user = User::find($request->user_id);

        $roleNames = $user->getRoleNames();

        return $this->successResponse($roleNames, 'Roles retrieved successfully!', 200);
    }

    public function createRole(Request $request,)
    {
        $role = Role::create(['name' => $request->input('role_name'),'guard_name' => 'web']);


        $permissionIds = $request->input('permission_ids'); 

        $permissions = Permission::whereIn('id', $permissionIds)->get();

        $role->syncPermissions($permissions);

        return $this->successResponse($role, 'Roles created successfully!', 201);
    }

    public function editRole(Request $request, $role_id)
{
    $role = Role::where('id', $role_id)->guard('web')->first();

    if (!$role) {
        // Handle role not found error
        return $this->errorResponse(false, 'Role not found!', 404);
    }

    $permissionIds = $request->input('permission_ids', []); // Initialize as empty array if not provided

    // Detach existing permissions from the role
    $role->permissions()->detach();

    // Attach the new set of permissions to the role
    foreach ($permissionIds as $permissionId) {
        $permission = Permission::where('id', $permissionId)->guard('sanctum')->first();
        if ($permission) {
            $role->givePermissionTo($permission);
        }
    }

    $role->name = $request->role_name;
    $role->save();

    return $this->successResponse($role, 'Role updated successfully!', 200);
}


    public function changeRole(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'roles' => 'required|array',
        ]);

        $user = User::find($request->user_id);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        return $this->successResponse( 'Roles updated successfully!', 201);
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
