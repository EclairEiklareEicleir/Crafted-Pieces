<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class YarnColor extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'hex_color',
        'preview_image_path',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    private static ?Collection $activeCache = null;

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function activeOptions(): Collection
    {
        return self::$activeCache ??= self::active()->ordered()->get();
    }

    public static function activeOptionsForProduct(Product $product): Collection
    {
        $restrictedColors = $product->relationLoaded('yarnColors')
            ? $product->yarnColors
            : $product->yarnColors()->get();

        $restrictedColors = $restrictedColors
            ->where('is_active', true)
            ->sortBy([['sort_order', 'asc'], ['name', 'asc']])
            ->values();

        return $restrictedColors->isNotEmpty()
            ? $restrictedColors
            : self::activeOptions();
    }

    public static function resetActiveCache(): void
    {
        self::$activeCache = null;
    }

    public static function slugFor(string $name): string
    {
        return Str::slug($name);
    }
}
