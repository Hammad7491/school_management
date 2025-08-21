<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // ⚠️ Dev-only hard reset (safe for local/staging)
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Permission::query()->delete();
        Role::query()->delete();

        // ---- Define permissions used by this app ----
        $perms = [
            // Dashboard
            'view dashboard',

            // Users / RBAC
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

            // ✅ Exams
            'create exams','view exams','edit exams','delete exams',
        ];

        foreach ($perms as $p) {
            Permission::create(['name' => $p, 'guard_name' => 'web']);
        }

        // ---- Roles ----
        $admin     = Role::create(['name' => 'Admin',     'guard_name' => 'web']);
        $principal = Role::create(['name' => 'Principal', 'guard_name' => 'web']);
        $teacher   = Role::create(['name' => 'Teacher',   'guard_name' => 'web']);
        // NOTE: No "Student" role — students use a separate dashboard without staff permissions.

        // Admin gets everything
        $admin->syncPermissions(Permission::all());

        // Principal: full academic control + exams (create/view/edit/delete)
        $principalPerms = [
            'view dashboard',
            'view users',

            'create classes','view classes','edit classes','delete classes',
            'create courses','view courses','edit courses','delete courses',
            'create students','view students','edit students','delete students',

            'create homeworks','view homeworks','edit homeworks','delete homeworks',

            // ✅ Exams (all)
            'create exams','view exams','edit exams','delete exams',
        ];
        $principal->syncPermissions($principalPerms);

        // Teacher: read core data, manage homework, and full exams (create/view/edit/delete)
        $teacherPerms = [
            'view dashboard',
            'view classes','view courses','view students',

            'create homeworks','view homeworks','edit homeworks',
            // 'delete homeworks', // enable if you want teachers to delete HW too

            // ✅ Exams (all)
            'create exams','view exams','edit exams','delete exams',
        ];
        $teacher->syncPermissions($teacherPerms);

        // Re-cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
