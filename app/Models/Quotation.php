<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'user_id',
        'quotation_number',
        'customer_name',
        'customer_email',
        'item_type',
        'design_theme',
        'preferred_size',
        'description',
        'status',
        'quoted_price',
    ];

    protected $casts = [
        'quoted_price' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
