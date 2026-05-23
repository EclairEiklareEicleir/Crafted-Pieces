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
        $bouquets = Category::where('slug', 'bouquets')->first();
        $plushies = Category::where('slug', 'plushies')->first();
        $accessories = Category::where('slug', 'accessories')->first();

        $imagePath = function (string $filename): string {
            $path = 'images/products/' . $filename;

            if (! file_exists(public_path($path))) {
                throw new RuntimeException("Product image not found: {$path}");
            }

            return $path;
        };

        $products = [
            [
                'name' => 'Cherry keychain',
                'slug' => 'cherry-keychain',
                'description' => 'A sweet cherry keychain with a polished handmade finish.',
                'price' => 329,
                'stock' => 10,
                'image' => $imagePath('Cherry keychain.png'),
                'product_type' => 'standard',
                'category_id' => $accessories?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Lily of the Valley keychain',
                'slug' => 'lily-of-the-valley-keychain',
                'description' => 'A delicate lily of the valley keychain with a clean handmade finish.',
                'price' => 379,
                'stock' => 9,
                'image' => $imagePath('Lily of the Valley keychain.png'),
                'product_type' => 'standard',
                'category_id' => $accessories?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mini Octopus keychain',
                'slug' => 'mini-octopus-keychain',
                'description' => 'A compact octopus keychain with soft handmade detail.',
                'price' => 499,
                'stock' => 8,
                'image' => $imagePath('Mini Octopus keychain – pink.png'),
                'product_type' => 'standard',
                'category_id' => $plushies?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mini Rose Bouquet keychain',
                'slug' => 'mini-rose-bouquet-keychain',
                'description' => 'A romantic mini rose bouquet keychain suited for gifts and display.',
                'price' => 449,
                'stock' => 8,
                'image' => $imagePath('Mini Rose Bouquet keychain – blue wrap.png'),
                'product_type' => 'standard',
                'category_id' => $bouquets?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mini Tulip keychain',
                'slug' => 'mini-tulip-keychain',
                'description' => 'A petite tulip keychain with a soft handmade finish.',
                'price' => 399,
                'stock' => 10,
                'image' => $imagePath('Mini Tulip keychain – white.png'),
                'product_type' => 'standard',
                'category_id' => $bouquets?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mushroom keychain',
                'slug' => 'mushroom-keychain',
                'description' => 'A whimsical mushroom keychain with playful color variants.',
                'price' => 389,
                'stock' => 12,
                'image' => $imagePath('Mushroom keychain – carrot.png'),
                'product_type' => 'standard',
                'category_id' => $accessories?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Smiley flower pot',
                'slug' => 'smiley-flower-pot',
                'description' => 'A cheerful flower pot piece with two tulips for a bright display.',
                'price' => 499,
                'stock' => 5,
                'image' => $imagePath('Smiley flower pot with two tulips.png'),
                'product_type' => 'standard',
                'category_id' => $bouquets?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Squashed Frog keychain',
                'slug' => 'squashed-frog-keychain',
                'description' => 'A cheerful squashed frog keychain with a soft stuffed shape.',
                'price' => 469,
                'stock' => 6,
                'image' => $imagePath('Squashed Frog keychain.png'),
                'product_type' => 'standard',
                'category_id' => $plushies?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Tulip pair keychain',
                'slug' => 'tulip-pair-keychain',
                'description' => 'A compact tulip pair keychain with a soft handmade finish.',
                'price' => 429,
                'stock' => 7,
                'image' => $imagePath('Tulip pair keychain.png'),
                'product_type' => 'standard',
                'category_id' => $bouquets?->id,
                'is_active' => true,
            ],
        ];

        $currentSlugs = collect($products)->pluck('slug')->all();

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }

        Product::whereNotIn('slug', $currentSlugs)->delete();

        $productIds = Product::whereIn('slug', $currentSlugs)->pluck('id', 'slug');

        ProductVariant::whereIn('product_id', $productIds->values())->delete();

        $variants = [
            'mini-octopus-keychain' => [
                ['name' => 'Pink', 'hex_color' => '#f79eb8', 'image_path' => $imagePath('Mini Octopus keychain – pink.png'), 'sort_order' => 1, 'is_default' => true],
                ['name' => 'Blue', 'hex_color' => '#8bb7f0', 'image_path' => $imagePath('Mini Octopus keychain – blue.png'), 'sort_order' => 2, 'is_default' => false],
            ],
            'mini-rose-bouquet-keychain' => [
                ['name' => 'Blue wrap', 'hex_color' => '#8fa8dd', 'image_path' => $imagePath('Mini Rose Bouquet keychain – blue wrap.png'), 'sort_order' => 1, 'is_default' => true],
                ['name' => 'Beige wrap', 'hex_color' => '#d8c3ab', 'image_path' => $imagePath('Mini Rose Bouquet keychain – beige wrap.png'), 'sort_order' => 2, 'is_default' => false],
            ],
            'mini-tulip-keychain' => [
                ['name' => 'White', 'hex_color' => '#ffffff', 'image_path' => $imagePath('Mini Tulip keychain – white.png'), 'sort_order' => 1, 'is_default' => true],
                ['name' => 'Blue', 'hex_color' => '#7da9f7', 'image_path' => $imagePath('Mini Tulip keychain – blue.png'), 'sort_order' => 2, 'is_default' => false],
                ['name' => 'Orange', 'hex_color' => '#f7a44a', 'image_path' => $imagePath('Mini Tulip keychain – orange.png'), 'sort_order' => 3, 'is_default' => false],
            ],
            'mushroom-keychain' => [
                ['name' => 'Carrot', 'hex_color' => '#f59a3f', 'image_path' => $imagePath('Mushroom keychain – carrot.png'), 'sort_order' => 1, 'is_default' => true],
                ['name' => 'Strawberry', 'hex_color' => '#df6c7d', 'image_path' => $imagePath('Mushroom keychain – strawberry.png'), 'sort_order' => 2, 'is_default' => false],
                ['name' => 'Watermelon', 'hex_color' => '#6bc47d', 'image_path' => $imagePath('Mushroom keychain – watermelon.png'), 'sort_order' => 3, 'is_default' => false],
            ],
        ];

        foreach ($variants as $slug => $variantRows) {
            $productId = $productIds[$slug] ?? null;

            if (! $productId) {
                continue;
            }

            foreach ($variantRows as $variantRow) {
                ProductVariant::create([
                    'product_id' => $productId,
                    ...$variantRow,
                ]);
            }
        }
    }
}
