<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'a@a'],
            ['name' => 'Admin User', 'password' => Hash::make('a')]
        );
        $admin->syncRoles(['Admin']);

        // Principal
        $principal = User::updateOrCreate(
            ['email' => 'principal@example.com'],
            ['name' => 'Principal User', 'password' => Hash::make('password')]
        );
        $principal->syncRoles(['Principal']);

        // Teacher
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Teacher User', 'password' => Hash::make('password')]
        );
        $teacher->syncRoles(['Teacher']);
    }
}
