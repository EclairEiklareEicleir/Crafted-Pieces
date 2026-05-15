<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@craftedpieces.com'
            ],
            [
                'name' => 'Admin Owner',
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ]
        );
    }
}
