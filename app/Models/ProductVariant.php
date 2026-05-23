<?php

namespace App\Models;

use App\Support\ProductImage;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'hex_color',
        'image_path',
        'sort_order',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return ProductImage::url($this->image_path);
    }

    public function getFloatingImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return ProductImage::floatingUrl($this->image_path) ?? $this->image_url;
    }
}
