<?php

namespace App\Models;

use App\Support\ProductImage;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'yarn_color',
        'hex_color',
        'size',
        'material',
        'design_style',
        'set_quantity',
        'packaging_option',
        'image_path',
        'sku',
        'price',
        'stock',
        'status',
        'sort_order',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
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

    public function getAvailabilityLabelAttribute(): string
    {
        if (($this->status ?? '') === 'inactive') {
            return 'Inactive';
        }

        return (int) ($this->stock ?? 0) > 0 ? 'Available' : 'Out of Stock';
    }
}
