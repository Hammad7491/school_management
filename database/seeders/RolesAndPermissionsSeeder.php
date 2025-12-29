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
        // Clear cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // ⚠️ Dev-only hard reset (safe for local/staging)
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Permission::query()->delete();
        Role::query()->delete();

        // ---- Define ALL permissions ----
        $permissions = [
            // Dashboard
            'view dashboard',

            // Users / RBAC
            'create users', 'view users', 'edit users', 'delete users',
            'create roles', 'view roles', 'edit roles', 'delete roles',
            'create permissions', 'view permissions', 'edit permissions', 'delete permissions',

            // Classes
            'create classes', 'view classes', 'edit classes', 'delete classes',

            // Subjects
            'create subjects', 'view subjects', 'edit subjects', 'delete subjects',

            // Courses
            'create courses', 'view courses', 'edit courses', 'delete courses',

            // Teacher Assignments
            'create teacher-assignments', 'view teacher-assignments', 'edit teacher-assignments', 'delete teacher-assignments',

            // Students
            'create students', 'view students', 'edit students', 'delete students',

            // Homeworks
            'create homeworks', 'view homeworks', 'edit homeworks', 'delete homeworks',

            // Exams
            'create exams', 'view exams', 'edit exams', 'delete exams',

            // Monthly Reports
            'create monthlyreports', 'view monthlyreports', 'edit monthlyreports', 'delete monthlyreports',

            // Results
            'upload results', 'view results', 'edit results', 'delete results',

            // Vacation Requests
            'create vacationrequests', 'view vacationrequests', 'edit vacationrequests', 'delete vacationrequests',
            'approve vacationrequests',

            // Admissions
            'create admissions', 'view admissions', 'edit admissions', 'delete admissions',

            // Notifications
            'create notifications', 'view notifications', 'edit notifications', 'delete notifications',
        ];

        // Create all permissions
        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        // ---- Create Roles ----
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $principal = Role::create(['name' => 'Principal', 'guard_name' => 'web']);
        $incharge = Role::create(['name' => 'Incharge', 'guard_name' => 'web']);
        $teacher = Role::create(['name' => 'Teacher', 'guard_name' => 'web']);

        // ---- Admin: Gets EVERYTHING ----
        $admin->syncPermissions(Permission::all());

        // ---- Principal: Full academic control ----
        $principalPerms = [
            'view dashboard',
            'view users',

            // Classes
            'create classes', 'view classes', 'edit classes', 'delete classes',

            // Subjects
            'create subjects', 'view subjects', 'edit subjects', 'delete subjects',

            // Courses
            'create courses', 'view courses', 'edit courses', 'delete courses',

            // Teacher Assignments
            'create teacher-assignments', 'view teacher-assignments', 'edit teacher-assignments', 'delete teacher-assignments',

            // Students
            'create students', 'view students', 'edit students', 'delete students',

            // Homeworks
            'create homeworks', 'view homeworks', 'edit homeworks', 'delete homeworks',

            // Exams
            'create exams', 'view exams', 'edit exams', 'delete exams',

            // Monthly Reports
            'create monthlyreports', 'view monthlyreports', 'edit monthlyreports', 'delete monthlyreports',

            // Results
            'upload results', 'view results', 'edit results', 'delete results',

            // Vacation Requests
            'view vacationrequests', 'approve vacationrequests', 'edit vacationrequests', 'delete vacationrequests',

            // Admissions
            'view admissions', 'delete admissions',

            // Notifications
            'create notifications', 'view notifications', 'edit notifications', 'delete notifications',
        ];
        $principal->syncPermissions($principalPerms);

        // ---- Incharge: Can manage their class fully ----
        $inchargePerms = [
            'view dashboard',

            // View only (cannot create/delete)
            'view classes', 'view courses', 'view subjects',

            // Students (can manage their class students)
            'create students', 'view students', 'edit students', 'delete students',

            // Homeworks (full control)
            'create homeworks', 'view homeworks', 'edit homeworks', 'delete homeworks',

            // Exams (full control)
            'create exams', 'view exams', 'edit exams', 'delete exams',

            // Monthly Reports (full control)
            'create monthlyreports', 'view monthlyreports', 'edit monthlyreports', 'delete monthlyreports',

            // Results (can upload and manage)
            'upload results', 'view results', 'edit results', 'delete results',

            // Vacation Requests (view only, no approval rights)
            'view vacationrequests',

            // View teacher assignments
            'view teacher-assignments',
        ];
        $incharge->syncPermissions($inchargePerms);

        // ---- Teacher: Limited to homework and viewing ----
        $teacherPerms = [
            'view dashboard',

            // View only
            'view classes', 'view courses', 'view subjects', 'view students',

            // Homeworks (can create and edit only their own)
            'create homeworks', 'view homeworks', 'edit homeworks',
            // NO 'delete homeworks'

            // Exams (view only)
            'view exams',

            // Monthly Reports (view only)
            'view monthlyreports',

            // Results (view only)
            'view results',

            // View teacher assignments
            'view teacher-assignments',
        ];
        $teacher->syncPermissions($teacherPerms);

        // Re-cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('✅ Roles and Permissions seeded successfully!');
        $this->command->info('Roles created: Admin, Principal, Incharge, Teacher');
    }
}