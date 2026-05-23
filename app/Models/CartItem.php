<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'yarn_color_id',
        'quantity',
        'price',
        'variant_name',
        'variant_hex_color',
        'variant_image_path',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(\App\Models\ProductVariant::class);
    }

    public function yarnColor()
    {
        return $this->belongsTo(YarnColor::class);
    }
}
