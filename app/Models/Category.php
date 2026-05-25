<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_path',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            $relativePath = Str::startsWith($this->image_path, 'storage/')
                ? Str::after($this->image_path, 'storage/')
                : $this->image_path;

            if (Storage::disk('public')->exists($relativePath)) {
                return Storage::url($relativePath);
            }
        }

        return match ($this->slug) {
            'bouquets' => asset('images/bouquet.png'),
            'accessories' => asset('images/accessories.png'),
            'plushies' => asset('images/plushies.png'),
            default => asset('images/bouquet.png'),
        };
    }
}
