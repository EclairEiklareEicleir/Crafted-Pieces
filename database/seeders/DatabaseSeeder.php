<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\User;
use App\Support\ShowcaseData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        $staffUser = User::updateOrCreate([
            'email' => 'admin@craftedpieces.local',
        ], [
            'name' => 'Crafted Pieces Staff',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STAFF,
            'email_verified_at' => now(),
        ]);

        $customerUser = User::updateOrCreate([
            'email' => 'customer@craftedpieces.local',
        ], [
            'name' => 'Crafted Pieces Customer',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        // Seed categories from ShowcaseData
        $showCaseCategories = ShowcaseData::categories();
        foreach ($showCaseCategories as $catData) {
            Category::updateOrCreate([
                'slug' => $catData['slug'],
            ], [
                'name' => $catData['name'],
                'emoji' => $catData['emoji'] ?? null,
            ]);
        }

        // Seed product tags from ShowcaseData
        $allTags = collect();
        foreach (ShowcaseData::products() as $product) {
            $allTags = $allTags->merge($product['tags'] ?? []);
        }

        $tagModels = [];
        foreach ($allTags->unique() as $tagName) {
            $tagModels[$tagName] = ProductTag::updateOrCreate([
                'slug' => Str::slug($tagName),
            ], [
                'name' => $tagName,
            ]);
        }

        // Seed products from ShowcaseData
        foreach (ShowcaseData::products() as $productData) {
            $category = Category::where('slug', Str::slug($productData['category']))->first();

            $product = Product::updateOrCreate([
                'slug' => $productData['slug'],
            ], [
                'name' => $productData['name'],
                'short_description' => $productData['short_description'],
                'description' => $productData['description'],
                'image' => $productData['image'],
                'category_id' => $category?->id,
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'status' => $productData['status'],
                'fulfillment' => $productData['fulfillment'],
            ]);

            // Attach tags
            $tagIds = [];
            foreach ($productData['tags'] ?? [] as $tagName) {
                if (isset($tagModels[$tagName])) {
                    $tagIds[] = $tagModels[$tagName]->id;
                }
            }

            if ($tagIds) {
                $product->tags()->sync($tagIds);
            }
        }

        // Seed orders and items from ShowcaseData
        foreach (ShowcaseData::orders() as $orderData) {
            $owner = User::where('email', $orderData['customer_email'])->first() ?? $customerUser;

            $order = Order::updateOrCreate([
                'order_number' => $orderData['id'],
            ], [
                'user_id' => $owner?->id,
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'total' => $orderData['total'],
                'status' => $orderData['status'],
                'payment_status' => $orderData['payment_status'],
                'payment_method' => $orderData['payment_method'] ?? null,
                'shipping_address' => $orderData['shipping_address'] ?? null,
                'created_at' => $orderData['created_at'] ?? now(),
                'updated_at' => $orderData['updated_at'] ?? now(),
            ]);

            $items = [];
            foreach ($orderData['items'] as $itemData) {
                $items[] = [
                    'product_name' => $itemData['product_name'],
                    'variant' => $itemData['variant'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ];
            }

            if ($items) {
                $order->items()->delete();
                $order->items()->createMany($items);
            }
        }

        // Seed quotations from ShowcaseData
        foreach (ShowcaseData::quotations() as $quotationData) {
            Quotation::updateOrCreate([
                'quotation_number' => $quotationData['id'],
            ], [
                'user_id' => $customerUser?->id,
                'customer_name' => $quotationData['customer_name'],
                'customer_email' => null,
                'item_type' => $quotationData['item_type'],
                'design_theme' => $quotationData['design_theme'],
                'preferred_size' => null,
                'description' => null,
                'status' => $quotationData['status'],
                'quoted_price' => $quotationData['quoted_price'] ?? null,
            ]);
        }
    }
}
