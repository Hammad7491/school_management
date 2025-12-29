<?php
// database/seeders/UserSeeder.php

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
            [
                'name' => 'Admin User',
                'password' => Hash::make('a')
            ]
        );
        $admin->syncRoles(['Admin']);

        // Principal
        $principal = User::updateOrCreate(
            ['email' => 'principal@example.com'],
            [
                'name' => 'Principal User',
                'password' => Hash::make('password')
            ]
        );
        $principal->syncRoles(['Principal']);

        // Incharge
        $incharge = User::updateOrCreate(
            ['email' => 'incharge@example.com'],
            [
                'name' => 'Incharge User',
                'password' => Hash::make('password')
            ]
        );
        $incharge->syncRoles(['Incharge']);

        // Teacher
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password')
            ]
        );
        $teacher->syncRoles(['Teacher']);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('Admin: a@a / a');
        $this->command->info('Principal: principal@example.com / password');
        $this->command->info('Incharge: incharge@example.com / password');
        $this->command->info('Teacher: teacher@example.com / password');
    }
}