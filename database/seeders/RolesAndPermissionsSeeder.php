<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'client can manage brand', 'ability' => 'manage_brand' ,'guard_name' => 'sanctum']);

        Permission::create(['name' => 'client can create request', 'ability' => 'create_request','guard_name' => 'sanctum']);
        Permission::create(['name' => 'client can view requests', 'ability' => 'view_request','guard_name' => 'sanctum']);
        Permission::create(['name' => 'client can delete request', 'ability' => 'delete_request','guard_name' => 'sanctum']);
        Permission::create(['name' => 'client can edit request', 'ability' => 'edit_request','guard_name' => 'sanctum']);

        Permission::create(['name' => 'client can manage teams', 'ability' => 'manage_teams','guard_name' => 'sanctum']);

        $role = Role::create(['name' => 'client','guard_name' => 'sanctum']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });


        $role = Role::create(['name' => 'admin','guard_name' => 'sanctum']);

        $permission = Permission::create(['name' => 'admin can view workspace', 'ability' => 'view_workspace','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'admin can manage coupons', 'ability' => 'manage_coupons','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'admin can manage subscription', 'ability' => 'manage_subscription','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'admin can manage staff', 'ability' => 'manage_staff','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'admin can manage portfolio', 'ability' => 'manage_portfolio','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'admin can view analytics', 'ability' => 'view_analytics','guard_name' => 'sanctum']);
        $role->givePermissionTo($permission->name);

        Permission::create(['name' => 'account manager', 'ability' => 'account_manager','guard_name' => 'sanctum']);

        //assign admin to the first user
        $user = User::find(1);
        $role = Role::whereName('admin')->first();
        $user->assignRole($role);

        //assign client to the second user
        $user = User::find(2);
        $role = Role::whereName('client')->first();
        $user->assignRole($role);


        $user = User::find(3);
        $permission = Permission::whereName('account manager')->first();
        $user->givePermissionTo($permission);

        $user = User::find(4);
        $permission = Permission::whereName('account manager')->first();
        $user->givePermissionTo($permission);



        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'admin']);
        // Permission::all()->each(function($permission) use($role) {
        //     $role->givePermissionTo($permission->name);
        // });
        $role = Role::create(['name' => 'pm','guard_name' => 'sanctum']);
        $role = Role::create(['name' => 'designer','guard_name' => 'sanctum']);
        // $role = Role::create(['name' => 'admin']);
    }
}
