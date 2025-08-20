<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // — Admin User —
        $admin = User::updateOrCreate(
            ['name' => 'Admin User'],
            [
                'email'    => 'a@a',
                'password' => Hash::make('a'),
            ]
        );
        $admin->syncRoles('Admin');

        // — School User —
        $school = User::updateOrCreate(
            ['name' => 'School User'],
            [
                'email'    => 'school@example.com',
                'password' => Hash::make('password'),
            ]
        );
       

        // — Student User —
      
    }
}
