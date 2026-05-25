<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Bouquets', 'slug' => 'bouquets'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Plushies', 'slug' => 'plushies'],
        ] as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}