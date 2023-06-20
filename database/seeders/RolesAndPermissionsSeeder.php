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
        Permission::create(['name' => 'client can manage brand']);

        Permission::create(['name' => 'client can create request']);
        Permission::create(['name' => 'client can view requests']);
        Permission::create(['name' => 'client can delete request']);
        Permission::create(['name' => 'client can edit request']);

        Permission::create(['name' => 'client can managment teams']);
        Permission::create(['name' => 'client can managment subscription']);

        $role = Role::create(['name' => 'client']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });

        Permission::create(['name' => 'admin can view workspace']);
        Permission::create(['name' => 'admin can manage coupons']);
        Permission::create(['name' => 'admin can manage subscription']);
        Permission::create(['name' => 'admin can manage staff']);
        Permission::create(['name' => 'admin can manage protifolio']);
        Permission::create(['name' => 'admin can view analytics']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'admin']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });
        $role = Role::create(['name' => 'pm']);
        $role = Role::create(['name' => 'designer']);
        $role = Role::create(['name' => 'admin']);
    }
}
