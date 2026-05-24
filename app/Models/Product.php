<?php

namespace App\Models;

use App\Support\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'product_type',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function variants()
    {
        return $this->hasMany(\App\Models\ProductVariant::class)->orderBy('sort_order')->orderBy('id');
    }

    public function defaultVariant()
    {
        return $this->hasOne(\App\Models\ProductVariant::class)->where('is_default', true);
    }

    public function yarnColors()
    {
        return $this->belongsToMany(YarnColor::class)
            ->withTimestamps()
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return 'https://placehold.co/600x600/png';
        }

        return ProductImage::url($this->image) ?? 'https://placehold.co/600x600/png';
    }

    public function getFloatingImageUrlAttribute(): string
    {
        if (! $this->image) {
            return 'https://placehold.co/600x600/png';
        }

        return ProductImage::floatingUrl($this->image) ?? $this->image_url;
    }

    public function variantForYarnColor(YarnColor $color): ?ProductVariant
    {
        $variants = $this->selectableVariants();

        return $variants->first(fn (ProductVariant $variant) => $this->variantMatchesYarnColor($variant, $color));
    }

    public function selectableVariants()
    {
        return $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->get();
    }

    public function availableVariants()
    {
        return $this->selectableVariants()->filter(function (ProductVariant $variant) {
            return ($variant->status ?? 'inactive') === 'active' && (int) ($variant->stock ?? 0) > 0;
        })->values();
    }

    public function syncMissingVariantsFromLegacyYarnColors(): void
    {
        $legacyColors = $this->relationLoaded('yarnColors')
            ? $this->yarnColors
            : $this->yarnColors()->get();

        if ($legacyColors->isEmpty()) {
            return;
        }

        $variants = $this->selectableVariants();

        foreach ($legacyColors as $index => $color) {
            $matchedVariant = $variants->first(fn (ProductVariant $variant) => $this->variantMatchesYarnColor($variant, $color));

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

            $this->variants()->create([
                'name' => $color->name ?: ($this->name . ' ' . ($index + 1)),
                'yarn_color' => $color->name,
                'hex_color' => $color->hex_color,
                'price' => $this->price,
                'stock' => $this->stock,
                'status' => 'active',
                'sort_order' => (int) $color->sort_order + ($index * 10),
                'is_default' => $variants->isEmpty() && $index === 0,
            ]);
        }

        $this->syncStockFromVariants();
    }

    public function variantMatchesYarnColor(ProductVariant $variant, YarnColor $color): bool
    {
        $variantName = Str::slug($variant->name);
        $variantYarnColor = Str::slug((string) ($variant->yarn_color ?? ''));
        $colorName = Str::slug($color->name);

        if ($variant->hex_color && $color->hex_color && strtolower($variant->hex_color) === strtolower($color->hex_color)) {
            return true;
        }

        return $variantYarnColor === $colorName
            || $variantName === $colorName
            || Str::contains($variantName, $colorName)
            || Str::contains($variantYarnColor, $colorName)
            || Str::contains($colorName, $variantName);
    }

    public function syncStockFromVariants(): void
    {
        if ($this->variants()->exists()) {
            $this->forceFill([
                'stock' => (int) $this->variants()->sum('stock'),
            ])->saveQuietly();
        }
    }
}
