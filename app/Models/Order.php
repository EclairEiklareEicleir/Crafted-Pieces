<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'shipping_address',
        'payment_method',
        'payment_status',
        'paymongo_checkout_id',
        'paymongo_payment_id',
        'paid_at',
        'subtotal',
        'platform_fee',
        'delivery_fee',
        'vat_amount',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getComputedSubtotalAttribute()
    {
        return $this->subtotal
            ?? $this->items->sum(fn ($i) => $i->quantity * $i->price);
    }
}