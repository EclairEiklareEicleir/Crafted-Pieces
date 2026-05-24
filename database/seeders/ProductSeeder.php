<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bouquets = Category::where('slug', 'bouquets')->firstOrFail();
        $plushies = Category::where('slug', 'plushies')->firstOrFail();
        $accessories = Category::where('slug', 'accessories')->firstOrFail();

        $imagePath = function (string $filename): string {
            $path = 'images/products/' . $filename;

            if (! file_exists(public_path($path))) {
                throw new RuntimeException("Product image not found: {$path}");
            }

            return $path;
        };

        $products = [
            [
                'slug' => 'cherry-keychain',
                'product' => [
                    'name' => 'Cherry keychain',
                    'description' => 'A sweet cherry keychain with a polished handmade finish.',
                    'price' => 329,
                    'stock' => 10,
                    'image' => $imagePath('Cherry keychain.png'),
                    'product_type' => 'standard',
                    'category_id' => $accessories->id,
                    'is_active' => true,
                ],
                'variants' => [],
            ],
            [
                'slug' => 'lily-of-the-valley-keychain',
                'product' => [
                    'name' => 'Lily of the Valley keychain',
                    'description' => 'A delicate lily of the valley keychain with a clean handmade finish.',
                    'price' => 379,
                    'stock' => 9,
                    'image' => $imagePath('Lily of the Valley keychain.png'),
                    'product_type' => 'standard',
                    'category_id' => $accessories->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Pink tulip',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#f79eb8',
                        'image_path' => 'product-variants/cACGMH6JNgtHqUwQhfu2EuBDSyYmbTO3rEvfxbYu.png',
                        'sku' => 'LILY-OF-THE-VALLEY-KEYCHAIN-PINK-TULIP-001',
                        'price' => 379,
                        'stock' => 9,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'slug' => 'mini-octopus-keychain',
                'product' => [
                    'name' => 'Mini Octopus keychain',
                    'description' => 'A compact octopus keychain with soft handmade detail.',
                    'price' => 499,
                    'stock' => 22,
                    'image' => $imagePath('Mini Octopus keychain – pink.png'),
                    'product_type' => 'standard',
                    'category_id' => $plushies->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Pinky',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#f79eb8',
                        'image_path' => 'images/products/Mini Octopus keychain – pink.png',
                        'sku' => 'MINI-OCTOPUS-KEYCHAIN-PINKY-001',
                        'price' => 120,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Bluey',
                        'yarn_color' => 'Blue',
                        'hex_color' => '#8bb7f0',
                        'image_path' => 'images/products/Mini Octopus keychain – blue.png',
                        'sku' => 'MINI-OCTOPUS-KEYCHAIN-BLUEY-001',
                        'price' => 119.97,
                        'stock' => 10,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'mini-rose-bouquet-keychain',
                'product' => [
                    'name' => 'Mini Rose Bouquet keychain',
                    'description' => 'A romantic mini rose bouquet keychain suited for gifts and display.',
                    'price' => 449,
                    'stock' => 24,
                    'image' => $imagePath('Mini Rose Bouquet keychain – blue wrap.png'),
                    'product_type' => 'standard',
                    'category_id' => $bouquets->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Blue wrap',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#8fa8dd',
                        'image_path' => 'images/products/Mini Rose Bouquet keychain – blue wrap.png',
                        'sku' => 'MINI-ROSE-BOUQUET-KEYCHAIN-BLUE-WRAP-001',
                        'price' => 119.99,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Beige wrap',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#d8c3ab',
                        'image_path' => 'images/products/Mini Rose Bouquet keychain – beige wrap.png',
                        'sku' => 'MINI-ROSE-BOUQUET-KEYCHAIN-BEIGE-WRAP-001',
                        'price' => 119.98,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'mini-tulip-keychain',
                'product' => [
                    'name' => 'Mini Tulip keychain',
                    'description' => 'A petite tulip keychain with a soft handmade finish.',
                    'price' => 399,
                    'stock' => 35,
                    'image' => $imagePath('Mini Tulip keychain – white.png'),
                    'product_type' => 'standard',
                    'category_id' => $bouquets->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'White',
                        'yarn_color' => 'White',
                        'hex_color' => '#ffffff',
                        'image_path' => 'images/products/Mini Tulip keychain – white.png',
                        'sku' => 'MINI-TULIP-KEYCHAIN-WHITE-001',
                        'price' => 399,
                        'stock' => 11,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Blue',
                        'yarn_color' => 'Blue',
                        'hex_color' => '#7da9f7',
                        'image_path' => 'images/products/Mini Tulip keychain – blue.png',
                        'sku' => 'MINI-TULIP-KEYCHAIN-BLUE-001',
                        'price' => 399,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                    [
                        'name' => 'Orange',
                        'yarn_color' => 'Orange',
                        'hex_color' => '#f7a44a',
                        'image_path' => 'images/products/Mini Tulip keychain – orange.png',
                        'sku' => 'MINI-TULIP-KEYCHAIN-ORANGE-001',
                        'price' => 399,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 3,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'mushroom-keychain',
                'product' => [
                    'name' => 'Mushroom keychain',
                    'description' => 'A whimsical mushroom keychain with playful color variants.',
                    'price' => 389,
                    'stock' => 164,
                    'image' => $imagePath('Mushroom keychain – carrot.png'),
                    'product_type' => 'standard',
                    'category_id' => $accessories->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Carrot',
                        'yarn_color' => 'Orange',
                        'hex_color' => '#f59a3f',
                        'image_path' => 'images/products/Mushroom keychain – carrot.png',
                        'sku' => 'MUSHROOM-KEYCHAIN-CARROT-001',
                        'price' => 389,
                        'stock' => 12,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Strawberry',
                        'yarn_color' => 'Red',
                        'hex_color' => '#df6c7d',
                        'image_path' => 'images/products/Mushroom keychain – strawberry.png',
                        'sku' => 'MUSHROOM-KEYCHAIN-STRAWBERRY-001',
                        'price' => 389,
                        'stock' => 30,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                    [
                        'name' => 'Watermelon',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#6bc47d',
                        'image_path' => 'images/products/Mushroom keychain – watermelon.png',
                        'sku' => 'MUSHROOM-KEYCHAIN-WATERMELON-001',
                        'price' => 389,
                        'stock' => 122,
                        'status' => 'active',
                        'sort_order' => 3,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'smiley-flower-pot',
                'product' => [
                    'name' => 'Smiley flower pot',
                    'description' => 'A cheerful flower pot piece with two tulips for a bright display.',
                    'price' => 499,
                    'stock' => 55,
                    'image' => $imagePath('Smiley flower pot with two tulips.png'),
                    'product_type' => 'standard',
                    'category_id' => $bouquets->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Pink Purple',
                        'yarn_color' => 'Pink and Purple',
                        'hex_color' => '#f79eb8',
                        'image_path' => 'product-variants/HCBYlaCrkAMxArihhWBwrzYmATYdL6AKSMEiaFK5.png',
                        'sku' => 'SMILEY-FLOWER-POT-PINK-PURPLE-001',
                        'price' => 499,
                        'stock' => 5,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Yellow Blue',
                        'yarn_color' => 'Yellow and Blue',
                        'hex_color' => null,
                        'image_path' => 'product-variants/Yf3NAlvPxG6BiHJRqv9ZIAR1VzeXfrt49owKHpaU.png',
                        'sku' => 'SMILEY-FLOWER-POT-YELLOW-BLUE-001',
                        'price' => 500,
                        'stock' => 50,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'squashed-frog-keychain',
                'product' => [
                    'name' => 'Squashed Frog keychain',
                    'description' => 'A cheerful squashed frog keychain with a soft stuffed shape.',
                    'price' => 469,
                    'stock' => 63,
                    'image' => $imagePath('Squashed Frog keychain.png'),
                    'product_type' => 'standard',
                    'category_id' => $plushies->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Green Frog',
                        'yarn_color' => 'Green',
                        'hex_color' => '#f79eb8',
                        'image_path' => 'product-variants/0yUCKJ6blrzwlain3WNbMaj8TYSd4qN7EvSg4AuM.png',
                        'sku' => 'SQUASHED-FROG-KEYCHAIN-GREEN-FROG-001',
                        'price' => 469,
                        'stock' => 6,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Light Green Frog',
                        'yarn_color' => 'Light Green',
                        'hex_color' => null,
                        'image_path' => 'product-variants/dICpudoRii0Zw1AYJ3Q1KO898XcoOEmE1owFsgko.png',
                        'sku' => 'SQUASHED-FROG-KEYCHAIN-LIGHT-GREEN-FROG-001',
                        'price' => 500,
                        'stock' => 23,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                    [
                        'name' => 'Cyan Frog',
                        'yarn_color' => 'Cyan',
                        'hex_color' => null,
                        'image_path' => 'product-variants/nlRHoAHLx16oZ2FPonkkT6HJFtHISXYWCfUVqkxU.png',
                        'sku' => 'SQUASHED-FROG-KEYCHAIN-CYAN-FROG-001',
                        'price' => 500,
                        'stock' => 34,
                        'status' => 'active',
                        'sort_order' => 3,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'slug' => 'tulip-pair-keychain',
                'product' => [
                    'name' => 'Tulip pair keychain',
                    'description' => 'A compact tulip pair keychain with a soft handmade finish.',
                    'price' => 429,
                    'stock' => 37,
                    'image' => $imagePath('Tulip pair keychain.png'),
                    'product_type' => 'standard',
                    'category_id' => $bouquets->id,
                    'is_active' => true,
                ],
                'variants' => [
                    [
                        'name' => 'Pink Tulip',
                        'yarn_color' => 'Pink',
                        'hex_color' => '#f79eb8',
                        'image_path' => 'product-variants/PgeR6QUKSnW3il4rvJusdYiyCIMq0ZiMCzel1x6j.png',
                        'sku' => 'TULIP-PAIR-KEYCHAIN-PINK-TULIP-001',
                        'price' => 429,
                        'stock' => 7,
                        'status' => 'active',
                        'sort_order' => 1,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Teal Tulip',
                        'yarn_color' => 'Teal',
                        'hex_color' => null,
                        'image_path' => 'product-variants/vFTohcECea6iAp2hcF1tZRnIN04IPcnU21LPpN9z.png',
                        'sku' => 'TULIP-PAIR-KEYCHAIN-TEAL-TULIP-001',
                        'price' => 500,
                        'stock' => 30,
                        'status' => 'active',
                        'sort_order' => 2,
                        'is_default' => false,
                    ],
                ],
            ],
        ];

        foreach ($products as $entry) {
            $product = Product::updateOrCreate(
                ['slug' => $entry['slug']],
                $entry['product']
            );

            foreach ($entry['variants'] as $variantData) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                    ],
                    [
                        'name' => $variantData['name'],
                        'yarn_color' => $variantData['yarn_color'],
                        'hex_color' => $variantData['hex_color'],
                        'image_path' => $variantData['image_path'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'status' => $variantData['status'],
                        'sort_order' => $variantData['sort_order'],
                        'is_default' => $variantData['is_default'],
                    ]
                );
            }
        }
    }
}
