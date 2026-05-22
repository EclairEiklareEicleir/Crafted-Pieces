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
        $variants = $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->get();

        return $variants->first(fn (ProductVariant $variant) => $this->variantMatchesYarnColor($variant, $color));
    }

    public function variantMatchesYarnColor(ProductVariant $variant, YarnColor $color): bool
    {
        $variantName = Str::slug($variant->name);
        $colorName = Str::slug($color->name);

        if ($variant->hex_color && $color->hex_color && strtolower($variant->hex_color) === strtolower($color->hex_color)) {
            return true;
        }

        return $variantName === $colorName
            || Str::contains($variantName, $colorName)
            || Str::contains($colorName, $variantName);
    }
}
