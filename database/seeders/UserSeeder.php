<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // OWNER / ADMIN
        User::updateOrCreate(
            [
                'email' => 'admin@craftedpieces.com'
            ],
            [
                'name' => 'Admin Owner',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'status' => 'active',
            ]
        );

        // USER 1
        User::updateOrCreate(
            [
                'email' => 'demoniczeno@gmail.com'
            ],
            [
                'name' => 'leklek',
                'password' => Hash::make('12'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // USER 2
        User::updateOrCreate(
            [
                'email' => 'charlesBenito@gmail.com'
            ],
            [
                'name' => 'charles',
                'password' => Hash::make('123'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // USER 3
        User::updateOrCreate(
            [
                'email' => 'toto@gmail.com'
            ],
            [
                'name' => 'toto',
                'password' => Hash::make('toto'),
                'role' => 'user',
                'status' => 'active',
            ]
        );
    }
}