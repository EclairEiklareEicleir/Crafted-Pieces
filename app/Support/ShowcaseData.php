<?php

namespace App\Support;

class ShowcaseData
{
    public static function categories(): array
    {
        return [
            ['name' => 'Keychains', 'slug' => 'keychains', 'count' => 6, 'emoji' => 'K'],
            ['name' => 'Bouquets', 'slug' => 'bouquets', 'count' => 3, 'emoji' => 'B'],
            ['name' => 'Plushies', 'slug' => 'plushies', 'count' => 2, 'emoji' => 'P'],
            ['name' => 'Wearables', 'slug' => 'wearables', 'count' => 1, 'emoji' => 'W'],
            ['name' => 'Custom', 'slug' => 'custom', 'count' => 2, 'emoji' => 'C'],
        ];
    }

    public static function products(): array
    {
        return [
            [
                'name' => 'Mini Octopus Keychain',
                'slug' => 'mini-octopus-keychain',
                'short_description' => 'A tiny crochet companion for your keys.',
                'description' => 'Hand-crocheted with soft cotton yarn. Great for gifts and daily carry.',
                'image' => '/images/products/Mini Octopus Keychain.jpg',
                'category' => 'Keychains',
                'price' => 120,
                'tags' => ['Bestseller', 'Ready Stock'],
                'stock' => 15,
                'status' => 'active',
                'fulfillment' => 'ready-stock',
            ],
            [
                'name' => 'Cherry Pair Keychain',
                'slug' => 'cherry-pair-keychain',
                'short_description' => 'Sweet cherry duo in soft yarn.',
                'description' => 'A handmade pair of cherries with sturdy ring hardware.',
                'image' => '/images/products/Cherry Pair Keychain.jpg',
                'category' => 'Keychains',
                'price' => 110,
                'tags' => ['Ready Stock'],
                'stock' => 10,
                'status' => 'active',
                'fulfillment' => 'ready-stock',
            ],
            [
                'name' => 'Tulip Crochet Bouquet',
                'slug' => 'tulip-crochet-bouquet',
                'short_description' => 'Everlasting tulips, carefully crafted.',
                'description' => 'A timeless crochet bouquet suitable for birthdays and celebrations.',
                'image' => '/images/products/Tulip Crochet Bouquet.jpg',
                'category' => 'Bouquets',
                'price' => 450,
                'tags' => ['Made to Order'],
                'stock' => 0,
                'status' => 'active',
                'fulfillment' => 'made-to-order',
            ],
            [
                'name' => 'Sunflower Bouquet',
                'slug' => 'sunflower-bouquet',
                'short_description' => 'Bright handmade sunflower arrangement.',
                'description' => 'Detailed petals and warm tones with wrapped stems.',
                'image' => '/images/products/Sunflower Bouquet.jpg',
                'category' => 'Bouquets',
                'price' => 380,
                'tags' => ['New Arrival'],
                'stock' => 0,
                'status' => 'active',
                'fulfillment' => 'made-to-order',
            ],
            [
                'name' => 'Mushroom Plushie',
                'slug' => 'mushroom-plushie',
                'short_description' => 'Soft amigurumi mushroom plushie.',
                'description' => 'A cozy desk buddy with hand-sewn details and premium stuffing.',
                'image' => '/images/products/Mushroom Plushie.jpg',
                'category' => 'Plushies',
                'price' => 300,
                'tags' => ['Customizable'],
                'stock' => 3,
                'status' => 'active',
                'fulfillment' => 'made-to-order',
            ],
            [
                'name' => 'Custom Amigurumi Character',
                'slug' => 'custom-amigurumi-character',
                'short_description' => 'Your favorite character, crocheted.',
                'description' => 'Share references and we will craft a one-of-one crochet character.',
                'image' => '/images/products/Custom Amigurumi Character.jpg',
                'category' => 'Custom',
                'price' => 500,
                'tags' => ['Quote Required'],
                'stock' => 0,
                'status' => 'active',
                'fulfillment' => 'made-to-order',
            ],
        ];
    }

    public static function featuredProducts(): array
    {
        return array_values(array_filter(self::products(), fn (array $product) => in_array('Bestseller', $product['tags'], true)));
    }

    public static function newArrivals(): array
    {
        return array_values(array_filter(self::products(), fn (array $product) => in_array('New Arrival', $product['tags'], true)));
    }

    public static function productsByCategory(?string $categorySlug): array
    {
        if (! $categorySlug || $categorySlug === 'all') {
            return self::products();
        }

        return array_values(array_filter(self::products(), fn (array $product) => strtolower($product['category']) === strtolower($categorySlug)));
    }

    public static function findProduct(string $slug): ?array
    {
        foreach (self::products() as $product) {
            if ($product['slug'] === $slug) {
                return $product;
            }
        }

        return null;
    }

    public static function steps(): array
    {
        return [
            ['title' => 'Browse Collections', 'desc' => 'Explore ready stock and made-to-order crochet pieces.'],
            ['title' => 'Customize Your Order', 'desc' => 'Choose colors, size, and style preferences.'],
            ['title' => 'Checkout', 'desc' => 'Review your cart and complete your purchase securely.'],
            ['title' => 'Receive Your Piece', 'desc' => 'We craft and ship your order with care.'],
        ];
    }

    public static function testimonials(): array
    {
        return [
            ['name' => 'Maria S.', 'text' => 'Beautiful stitching and lovely packaging.', 'rating' => 5],
            ['name' => 'Anna C.', 'text' => 'Custom bouquet looked exactly how I imagined it.', 'rating' => 5],
            ['name' => 'Sofia R.', 'text' => 'Great communication and quality.', 'rating' => 5],
        ];
    }

    public static function cartItems(): array
    {
        return [
            ['name' => 'Mini Octopus Keychain', 'quantity' => 2, 'price' => 120],
            ['name' => 'Cherry Pair Keychain', 'quantity' => 1, 'price' => 110],
        ];
    }

    public static function orders(): array
    {
        return [
            [
                'id' => 'ORD-001',
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria@example.com',
                'items' => [
                    ['product_name' => 'Mini Octopus Keychain', 'quantity' => 2, 'price' => 120],
                    ['product_name' => 'Cherry Pair Keychain', 'quantity' => 1, 'price' => 110],
                ],
                'total' => 350,
                'status' => 'in-progress',
                'payment_status' => 'paid',
                'payment_method' => 'GCash',
                'shipping_address' => 'Quezon City, Metro Manila',
                'created_at' => '2026-04-01',
                'updated_at' => '2026-04-02',
            ],
            [
                'id' => 'ORD-002',
                'customer_name' => 'Anna Cruz',
                'customer_email' => 'anna@example.com',
                'items' => [
                    ['product_name' => 'Tulip Crochet Bouquet', 'variant' => 'Pink and White', 'quantity' => 1, 'price' => 600],
                ],
                'total' => 600,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => 'Bank Transfer',
                'shipping_address' => 'Makati City, Metro Manila',
                'created_at' => '2026-04-03',
                'updated_at' => '2026-04-03',
            ],
            [
                'id' => 'ORD-003',
                'customer_name' => 'Sofia Reyes',
                'customer_email' => 'sofia@example.com',
                'items' => [
                    ['product_name' => 'Sunflower Bouquet', 'quantity' => 1, 'price' => 380],
                ],
                'total' => 380,
                'status' => 'shipped',
                'payment_status' => 'paid',
                'payment_method' => 'Maya',
                'shipping_address' => 'Cebu City, Cebu',
                'created_at' => '2026-04-04',
                'updated_at' => '2026-04-05',
            ],
        ];
    }

    public static function quotations(): array
    {
        return [
            [
                'id' => 'QR-001',
                'customer_name' => 'Trisha Lim',
                'item_type' => 'Custom Bouquet',
                'design_theme' => 'Romantic Valentine',
                'status' => 'pending',
            ],
            [
                'id' => 'QR-002',
                'customer_name' => 'Marc Villanueva',
                'item_type' => 'Amigurumi Character',
                'design_theme' => 'Character Inspired',
                'status' => 'quoted',
                'quoted_price' => 650,
            ],
        ];
    }

    public static function stats(): array
    {
        return [
            ['label' => 'Total Products', 'value' => count(self::products())],
            ['label' => 'Total Orders', 'value' => count(self::orders())],
            [
                'label' => 'Pending Quotations',
                'value' => count(array_filter(self::quotations(), fn (array $quotation) => $quotation['status'] === 'pending')),
            ],
            ['label' => 'Revenue (Demo)', 'value' => 'PHP 1,330'],
        ];
    }

    public static function adminFaq(): array
    {
        return [
            ['q' => 'How do I handle missing order updates?', 'a' => 'Check the timeline first, then verify payment and courier notes.'],
            ['q' => 'What if a quotation is too complex?', 'a' => 'Reply with a scoped estimate and request clearer references.'],
            ['q' => 'When should I mark shipped?', 'a' => 'Set shipped only after a valid tracking reference exists.'],
        ];
    }

    public static function faq(): array
    {
        return [
            ['q' => 'How long does a custom order take?', 'a' => 'Most custom pieces take 5 to 10 days depending on complexity.'],
            ['q' => 'Do you accept quotation requests?', 'a' => 'Yes. Share your idea and we will reply with price and timeline.'],
            ['q' => 'What payment methods are supported?', 'a' => 'GCash, Maya, and bank transfer are currently supported.'],
        ];
    }
}
