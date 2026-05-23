<?php

namespace App\Support;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\YarnColor;
use Illuminate\Support\Collection;

class SessionCart
{
    public const KEY = 'cart';

    public static function add(Product $product, ?ProductVariant $variant, YarnColor $yarnColor, int $quantity): void
    {
        $cart = self::raw();
        $key = self::itemKey($product->id, $yarnColor->id);
        $now = now()->toDateTimeString();
        $existing = $cart[$key] ?? [];

        $cart[$key] = [
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'yarn_color_id' => $yarnColor->id,
            'quantity' => ((int) ($existing['quantity'] ?? 0)) + $quantity,
            'price' => (float) $product->price,
            'variant_name' => $yarnColor->name,
            'variant_hex_color' => $yarnColor->hex_color,
            'variant_image_path' => $variant?->image_path ?? $product->image,
            'created_at' => $existing['created_at'] ?? $now,
            'updated_at' => $now,
        ];

        self::put($cart);
    }

    public static function update(string $key, int $quantity): bool
    {
        $cart = self::raw();

        if (! isset($cart[$key])) {
            return false;
        }

        $cart[$key]['quantity'] = $quantity;
        $cart[$key]['updated_at'] = now()->toDateTimeString();

        self::put($cart);

        return true;
    }

    public static function remove(string $key): bool
    {
        $cart = self::raw();

        if (! isset($cart[$key])) {
            return false;
        }

        unset($cart[$key]);
        self::put($cart);

        return true;
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    public static function count(): int
    {
        return collect(self::raw())->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
    }

    public static function quantityFor(Product $product, YarnColor $yarnColor): int
    {
        $item = self::raw()[self::itemKey($product->id, $yarnColor->id)] ?? null;

        return (int) ($item['quantity'] ?? 0);
    }

    public static function item(string $key): ?CartItem
    {
        return self::items()->firstWhere('id', $key);
    }

    public static function items(): Collection
    {
        $rawCart = self::raw();

        if ($rawCart === []) {
            return collect();
        }

        $rows = collect($rawCart)
            ->filter(fn ($item) => is_array($item) && ! empty($item['product_id']));

        if ($rows->isEmpty()) {
            self::clear();

            return collect();
        }

        $products = Product::with(['defaultVariant', 'variants', 'yarnColors'])
            ->whereIn('id', $rows->pluck('product_id')->map(fn ($id) => (int) $id)->unique())
            ->get()
            ->keyBy('id');

        $variants = ProductVariant::whereIn(
            'id',
            $rows->pluck('product_variant_id')->filter()->map(fn ($id) => (int) $id)->unique()
        )->get()->keyBy('id');

        $yarnColors = YarnColor::whereIn(
            'id',
            $rows->pluck('yarn_color_id')->filter()->map(fn ($id) => (int) $id)->unique()
        )->get()->keyBy('id');

        $validCart = [];
        $items = $rows
            ->sortByDesc(fn ($data) => $data['updated_at'] ?? $data['created_at'] ?? '')
            ->map(function (array $data, $key) use ($products, $variants, $yarnColors, &$validCart) {
                $key = (string) $key;
                $product = $products->get((int) $data['product_id']);

                if (! $product) {
                    return null;
                }

                $variant = ! empty($data['product_variant_id'])
                    ? $variants->get((int) $data['product_variant_id'])
                    : null;

                $yarnColor = ! empty($data['yarn_color_id'])
                    ? $yarnColors->get((int) $data['yarn_color_id'])
                    : null;

                $normalized = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'yarn_color_id' => $yarnColor?->id,
                    'quantity' => max(1, (int) ($data['quantity'] ?? 1)),
                    'price' => (float) ($data['price'] ?? $product->price),
                    'variant_name' => $data['variant_name'] ?? $yarnColor?->name,
                    'variant_hex_color' => $data['variant_hex_color'] ?? $yarnColor?->hex_color,
                    'variant_image_path' => $data['variant_image_path'] ?? $variant?->image_path ?? $product->image,
                    'created_at' => $data['created_at'] ?? now()->toDateTimeString(),
                    'updated_at' => $data['updated_at'] ?? $data['created_at'] ?? now()->toDateTimeString(),
                ];

                $validCart[$key] = $normalized;

                $item = new CartItem($normalized);
                $item->setAttribute('id', $key);
                $item->exists = false;
                $item->setRelation('product', $product);
                $item->setRelation('productVariant', $variant);
                $item->setRelation('yarnColor', $yarnColor);

                return $item;
            })
            ->filter()
            ->values();

        if ($validCart != $rawCart) {
            self::put($validCart);
        }

        return $items;
    }

    public static function itemKey(int $productId, ?int $yarnColorId): string
    {
        return 'p_' . $productId . '_y_' . ($yarnColorId ?: 'none');
    }

    private static function raw(): array
    {
        $cart = session(self::KEY, []);

        return is_array($cart) ? $cart : [];
    }

    private static function put(array $cart): void
    {
        if ($cart === []) {
            self::clear();

            return;
        }

        session()->put(self::KEY, $cart);
    }
}
