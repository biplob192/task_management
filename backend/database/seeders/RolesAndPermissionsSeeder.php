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
     */
    public function run(): void
    {
        // Roles
        // $superAdminRole         = Role::create(['name' => 'Super Admin', 'guard_name' => 'api']);
        // $adminRole              = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        // $editorRole             = Role::create(['name' => 'Editor', 'guard_name' => 'api']);
        // $employeeRole           = Role::create(['name' => 'Employee', 'guard_name' => 'api']);
        // $userRole               = Role::create(['name' => 'User', 'guard_name' => 'api']);

        // Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        // Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        // Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        // Role::create(['name' => 'Employee', 'guard_name' => 'web']);
        // Role::create(['name' => 'User', 'guard_name' => 'web']);

        $superAdminRole         = Role::create(['name' => 'Super Admin']);
        $adminRole              = Role::create(['name' => 'Admin']);
        $editorRole             = Role::create(['name' => 'Editor']);
        $employeeRole           = Role::create(['name' => 'Employee']);
        $userRole               = Role::create(['name' => 'User']);


        // Permissions
        // $createPermission       = Permission::create(['name' => 'create', 'guard_name' => 'api']);
        // $readPermission         = Permission::create(['name' => 'read', 'guard_name' => 'api']);
        // $updatePermission       = Permission::create(['name' => 'update', 'guard_name' => 'api']);
        // $deletePermission       = Permission::create(['name' => 'delete', 'guard_name' => 'api']);
        // $createAdminPermission  = Permission::create(['name' => 'create.admins', 'guard_name' => 'api']);

        $createPermission               = Permission::create(['name' => 'create']);
        $readPermission                 = Permission::create(['name' => 'read']);
        $updatePermission               = Permission::create(['name' => 'update']);
        $deletePermission               = Permission::create(['name' => 'delete']);
        $createAdminPermission          = Permission::create(['name' => 'create.admin']);
        $createSuperAdminPermission     = Permission::create(['name' => 'create.super_admin']);

        // Assign permissions to super_admin role
        $superAdminRole->givePermissionTo($createPermission);
        $superAdminRole->givePermissionTo($readPermission);
        $superAdminRole->givePermissionTo($updatePermission);
        $superAdminRole->givePermissionTo($deletePermission);
        $superAdminRole->givePermissionTo($createAdminPermission);
        $superAdminRole->givePermissionTo($createSuperAdminPermission);

        // Assign permissions to admin role
        $adminRole->givePermissionTo($createPermission);
        $adminRole->givePermissionTo($readPermission);
        $adminRole->givePermissionTo($updatePermission);
        $adminRole->givePermissionTo($deletePermission);
        $adminRole->givePermissionTo($createAdminPermission);

        // Assign permissions to editor role
        $editorRole->givePermissionTo($createPermission);
        $editorRole->givePermissionTo($readPermission);
        $editorRole->givePermissionTo($updatePermission);
        $editorRole->givePermissionTo($deletePermission);

        // Assign permissions to employee role
        $employeeRole->givePermissionTo($createPermission);
        $employeeRole->givePermissionTo($readPermission);
        $employeeRole->givePermissionTo($updatePermission);

        // Assign permissions to user role
        $userRole->givePermissionTo($readPermission);
        $userRole->givePermissionTo($updatePermission);
    }
}
