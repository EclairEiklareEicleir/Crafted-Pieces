<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Bouquets',
                'slug' => 'bouquets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plushies',
                'slug' => 'plushies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
