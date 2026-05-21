<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the About Section table with default content.
     */
    public function run(): void
    {
        AboutSection::firstOrCreate(
            ['id' => 1],
            [
                'heading' => 'A small studio with a handmade focus',
                'content' => 'Crafted Pieces creates crochet gifts, accessories, and custom pieces with a careful process and consistent quality. Every item is handmade with care and attention to detail, ensuring that each piece is unique and special.',
            ]
        );
    }
}
