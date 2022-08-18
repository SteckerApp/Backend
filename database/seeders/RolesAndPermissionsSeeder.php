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
        Permission::create(['name' => 'client can managment brand']);

        Permission::create(['name' => 'client can create request']);
        Permission::create(['name' => 'client can view requests']);
        Permission::create(['name' => 'client can delete request']);
        Permission::create(['name' => 'client can edit request']);

        Permission::create(['name' => 'client can managment teams']);
        Permission::create(['name' => 'client can managment subscription']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'client']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });
    }
}
