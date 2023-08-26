<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
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

        PermissionGroup::create(['name' => 'Workspace and Request access']);
        PermissionGroup::create(['name' => 'Account Manager']);
        PermissionGroup::create(['name' => 'Analytics']);
        PermissionGroup::create(['name' => 'Promo Code Management']);
        PermissionGroup::create(['name' => 'Portfolio Management']);
        PermissionGroup::create(['name' => 'Plans & Addons']);
        PermissionGroup::create(['name' => 'Team']);

        // create permissions
        Permission::create(['name' => 'client can manage plan', 'ability' => 'manage_brand' ,'guard_name' => 'sanctum']);

        // Permission::create(['name' => 'client can manage brand', 'ability' => 'manage_brand' ,'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'client can create request', 'ability' => 'create_request','guard_name' => 'sanctum']);
        // Permission::create(['name' => 'client can view requests', 'ability' => 'view_request','guard_name' => 'sanctum']);
        // Permission::create(['name' => 'client can delete request', 'ability' => 'delete_request','guard_name' => 'sanctum']);
        // Permission::create(['name' => 'client can edit request', 'ability' => 'edit_request','guard_name' => 'sanctum']);

        // Permission::create(['name' => 'client can manage teams', 'ability' => 'manage_teams','guard_name' => 'sanctum']);

        $role = Role::create(['name' => 'client','guard_name' => 'sanctum']);
        Permission::all()->each(function($permission) use($role) {
            $role->givePermissionTo($permission->name);
        });


        $role = Role::create(['name' => 'admin','guard_name' => 'sanctum']);

        $permission = Permission::create(['name' => 'view workspace and board', 'ability' => 'view_workspace_and_board','guard_name' => 'sanctum', 'permission_group_id' => '1' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'remove users from workspace and board', 'ability' => 'remove_users_from_workspace_and_board','guard_name' => 'sanctum', 'permission_group_id' => '1' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'add users to workspace and board', 'ability' => 'add_users_to_workspace_and_board','guard_name' => 'sanctum', 'permission_group_id' => '1' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view all workspace', 'ability' => 'view_all_workspace','guard_name' => 'sanctum', 'permission_group_id' => '1' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'lias with customers', 'ability' => 'lias_with_customers','guard_name' => 'sanctum', 'permission_group_id' => '2' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view orders', 'ability' => 'view_orders','guard_name' => 'sanctum', 'permission_group_id' => '3' ]);

        $permission = Permission::create(['name' => 'view users', 'ability' => 'view_users','guard_name' => 'sanctum', 'permission_group_id' => '3' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view customers', 'ability' => 'view_customers','guard_name' => 'sanctum', 'permission_group_id' => '3' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view affiliates', 'ability' => 'view_affiliates','guard_name' => 'sanctum', 'permission_group_id' => '3' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'approve affiliates payout', 'ability' => 'approve_affiliates_payout','guard_name' => 'sanctum', 'permission_group_id' => '3' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view promo code', 'ability' => 'view_promo_code','guard_name' => 'sanctum', 'permission_group_id' => '4' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'add promo code', 'ability' => 'add_promo_code','guard_name' => 'sanctum', 'permission_group_id' => '4' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'edit promo code', 'ability' => 'edit_promo_code','guard_name' => 'sanctum', 'permission_group_id' => '4' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'delete promo code', 'ability' => 'delete_promo_code','guard_name' => 'sanctum', 'permission_group_id' => '4' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view porfolio', 'ability' => 'view_portfolio','guard_name' => 'sanctum', 'permission_group_id' => '5' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'add plans and addons', 'ability' => 'add_plans_and_addons','guard_name' => 'sanctum', 'permission_group_id' => '6' ]);
        $role->givePermissionTo($permission->name);
        
        $permission = Permission::create(['name' => 'edit plans and addons', 'ability' => 'edit_plans_and_addons','guard_name' => 'sanctum', 'permission_group_id' => '6' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'delete plans and addons', 'ability' => 'delete_plans_and_addons','guard_name' => 'sanctum', 'permission_group_id' => '6' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'view team members', 'ability' => 'view_team_members','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'add team members', 'ability' => 'add_team_members','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'delete team members', 'ability' => 'delete_team_members','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'create roles and permissions', 'ability' => 'create_roles_and_permissions','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'edit roles and permissions', 'ability' => 'edit_roles_and_permissions','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        $permission = Permission::create(['name' => 'update team member role', 'ability' => 'update_team_member_role','guard_name' => 'sanctum', 'permission_group_id' => '7' ]);
        $role->givePermissionTo($permission->name);

        //assign admin to the first user
        $user = User::find(1);
        $role = Role::whereName('admin')->first();
        $user->assignRole($role);

        //assign client to the second user
        $user = User::find(2);
        $role = Role::whereName('client')->first();
        $user->assignRole($role);


        $user = User::find(3);
        $permission = Permission::whereName('lias with customers')->first();
        $user->givePermissionTo($permission);

        $user = User::find(4);
        $permission = Permission::whereName('lias with customers')->first();
        $user->givePermissionTo($permission);

    }
}
