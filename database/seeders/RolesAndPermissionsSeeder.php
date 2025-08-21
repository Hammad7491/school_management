<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // HARD RESET current RBAC tables (safe in dev/staging; be careful in prod)
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Permission::query()->delete();
        Role::query()->delete();

        // --- Define permissions (only what this app needs) ---
        $perms = [
            // dashboard
            'view dashboard',

            // Users / Roles / Permissions (staff management)
            'create users','view users','edit users','delete users',
            'create roles','view roles','edit roles','delete roles',
            'create permissions','view permissions','edit permissions','delete permissions',

            // Classes
            'create classes','view classes','edit classes','delete classes',

            // Courses
            'create courses','view courses','edit courses','delete courses',

            // Students
            'create students','view students','edit students','delete students',

            // Homeworks
            'create homeworks','view homeworks','edit homeworks','delete homeworks',
        ];

        foreach ($perms as $p) {
            Permission::create(['name' => $p, 'guard_name' => 'web']);
        }

        // --- Roles ---
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $principal = Role::create(['name' => 'Principal', 'guard_name' => 'web']);
        $teacher = Role::create(['name' => 'Teacher', 'guard_name' => 'web']);
        // NOTE: No "Student" role – students use a separate dashboard and get no staff permissions.

        // Admin gets everything
        $admin->syncPermissions(Permission::all());

        // Principal: full control of academic data; can view users (but not manage RBAC)
        $principalPerms = [
            'view dashboard',
            // Users (read-only)
            'view users',

            // Classes/Courses/Students full
            'create classes','view classes','edit classes','delete classes',
            'create courses','view courses','edit courses','delete courses',
            'create students','view students','edit students','delete students',

            // Homework full
            'create homeworks','view homeworks','edit homeworks','delete homeworks',
        ];
        $principal->syncPermissions($principalPerms);

        // Teacher: read classes/courses/students; can manage homework (no delete by default)
        $teacherPerms = [
            'view dashboard',
            'view classes','view courses','view students',
            'create homeworks','view homeworks','edit homeworks',
            // Add 'delete homeworks' here if you want teachers to be able to delete:
            // 'delete homeworks',
        ];
        $teacher->syncPermissions($teacherPerms);

        // Re-cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
