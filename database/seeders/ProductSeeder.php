<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bouquets = Category::where('slug', 'bouquets')->first();
        $plushies = Category::where('slug', 'plushies')->first();
        $accessories = Category::where('slug', 'accessories')->first();

        Product::insert([

            [
                'name' => 'Tulip Bouquet',
                'slug' => 'tulip-bouquet',
                'description' => 'Handmade crochet tulip bouquet perfect for gifts and room decoration.',
                'price' => 799,
                'stock' => 5,
                'image' => 'https://placehold.co/600x600/png',
                'product_type' => 'standard',
                'category_id' => $bouquets?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Mini Bear Plushie',
                'slug' => 'mini-bear-plushie',
                'description' => 'Soft crochet bear plushie handmade with premium yarn.',
                'price' => 499,
                'stock' => 8,
                'image' => 'https://placehold.co/600x600/png',
                'product_type' => 'standard',
                'category_id' => $plushies?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Custom Crochet Keychain',
                'slug' => 'custom-crochet-keychain',
                'description' => 'Personalized crochet keychain made based on your requested design.',
                'price' => 299,
                'stock' => 999,
                'image' => 'https://placehold.co/600x600/png',
                'product_type' => 'custom',
                'category_id' => $accessories?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
