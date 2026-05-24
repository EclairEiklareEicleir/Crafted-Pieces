<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Product::query()
            ->with(['yarnColors', 'variants'])
            ->orderBy('id')
            ->get()
            ->each(function (Product $product): void {
                $legacyColors = $product->yarnColors;

                if ($legacyColors->isEmpty()) {
                    return;
                }

                $existingVariants = $product->variants;

                foreach ($legacyColors as $index => $color) {
                    $matchedVariant = $existingVariants->first(function (ProductVariant $variant) use ($color): bool {
                        $variantName = Str::slug((string) ($variant->name ?? ''));
                        $variantYarnColor = Str::slug((string) ($variant->yarn_color ?? ''));
                        $colorName = Str::slug((string) ($color->name ?? ''));

                        return (
                            filled($variant->hex_color)
                            && filled($color->hex_color)
                            && strtolower($variant->hex_color) === strtolower($color->hex_color)
                        ) || $variantName === $colorName
                            || $variantYarnColor === $colorName
                            || Str::contains($variantName, $colorName)
                            || Str::contains($variantYarnColor, $colorName);
                    });

                    if ($matchedVariant) {
                        $updates = [];

                        if (! filled($matchedVariant->yarn_color)) {
                            $updates['yarn_color'] = $color->name;
                        }

                        if (! filled($matchedVariant->hex_color) && filled($color->hex_color)) {
                            $updates['hex_color'] = $color->hex_color;
                        }

                        if ($updates !== []) {
                            $matchedVariant->forceFill($updates)->saveQuietly();
                        }

                        continue;
                    }

                    $product->variants()->create([
                        'name' => $color->name ?: ($product->name . ' ' . ($index + 1)),
                        'yarn_color' => $color->name,
                        'hex_color' => $color->hex_color,
                        'price' => $product->price,
                        'stock' => $product->stock,
                        'status' => 'active',
                        'sort_order' => (int) $color->sort_order + ($index * 10),
                        'is_default' => $existingVariants->isEmpty() && $index === 0,
                    ]);
                }

                $product->syncStockFromVariants();
            });
    }

    public function down(): void
    {
        // Safe backfill only; do not delete variants on rollback.
    }
};