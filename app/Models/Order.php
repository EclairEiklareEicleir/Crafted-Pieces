<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_session_id',
        'public_reference',
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

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (! empty($order->public_reference)) {
                return;
            }

            do {
                $reference = 'ORD-' . Str::upper(Str::random(10));
            } while (static::where('public_reference', $reference)->exists());

            $order->public_reference = $reference;
        });
    }

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