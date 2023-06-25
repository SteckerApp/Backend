<?php

namespace Database\Seeders;

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
        Permission::create(['name' => 'client can manage brand', 'ability' => 'manage_brand' ]);

        Permission::create(['name' => 'client can create request', 'ability' => 'create_request']);
        Permission::create(['name' => 'client can view requests', 'ability' => 'view_request']);
        Permission::create(['name' => 'client can delete request', 'ability' => 'delete_request']);
        Permission::create(['name' => 'client can edit request', 'ability' => 'edit_request']);

        Permission::create(['name' => 'client can manage teams', 'ability' => 'manage_teams']);

        $role = Role::create(['name' => 'client']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });

        Permission::create(['name' => 'admin can view workspace', 'ability' => 'view_workspace']);
        Permission::create(['name' => 'admin can manage coupons', 'ability' => 'manage_coupons']);
        Permission::create(['name' => 'admin can manage subscription', 'ability' => 'manage_subscription']);
        Permission::create(['name' => 'admin can manage staff', 'ability' => 'manage_staff']);
        Permission::create(['name' => 'admin can manage portfolio', 'ability' => 'manage_portfolio']);
        Permission::create(['name' => 'admin can view analytics', 'ability' => 'view_analytics']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'admin']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });
        $role = Role::create(['name' => 'pm']);
        $role = Role::create(['name' => 'designer']);
        // $role = Role::create(['name' => 'admin']);
    }
}
