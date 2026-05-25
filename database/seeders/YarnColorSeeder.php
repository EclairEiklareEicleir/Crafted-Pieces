<?php

namespace Database\Seeders;

use App\Models\YarnColor;
use Illuminate\Database\Seeder;

class YarnColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'White', 'hex_color' => '#ffffff'],
            ['name' => 'Pink', 'hex_color' => '#f79eb8'],
            ['name' => 'Blue', 'hex_color' => '#8bb7f0'],
            ['name' => 'Orange', 'hex_color' => '#f7a44a'],
            ['name' => 'Beige', 'hex_color' => '#d8c3ab'],
            ['name' => 'Red', 'hex_color' => '#d83b4a'],
            ['name' => 'Green', 'hex_color' => '#6bc47d'],
            ['name' => 'Yellow', 'hex_color' => '#f4d35e'],
            ['name' => 'Lavender', 'hex_color' => '#bca7f2'],
            ['name' => 'Brown', 'hex_color' => '#8a5a44'],
            ['name' => 'Carrot', 'hex_color' => '#f59a3f'],
            ['name' => 'Strawberry', 'hex_color' => '#df6c7d'],
            ['name' => 'Watermelon', 'hex_color' => '#6bc47d'],
        ];

        foreach ($colors as $index => $color) {
            YarnColor::updateOrCreate(
                ['slug' => YarnColor::slugFor($color['name'])],
                [
                    ...$color,
                    'slug' => YarnColor::slugFor($color['name']),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }

        YarnColor::resetActiveCache();
    }
}
