<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    public const ACTIVE_ORDER_STATUSES = [
        'pending',
        'awaiting_payment',
        'processing',
        'shipped',
        'out_for_delivery',
        'delivered',
    ];

    public const HISTORY_ORDER_STATUSES = [
        'completed',
        'received',
        'cancelled',
        'refunded',
        'rejected',
        'failed',
    ];

    public const ORDER_STATUSES = [
        'pending',
        'awaiting_payment',
        'processing',
        'shipped',
        'out_for_delivery',
        'delivered',
        'received',
        'completed',
        'cancelled',
        'refunded',
        'rejected',
        'failed',
    ];

    public const PAYMENT_STATUSES = [
        'paid',
        'unpaid',
        'pending',
        'awaiting_payment',
        'failed',
        'refunded',
        'expired',
        'cancelled',
    ];

    public const REVENUE_ORDER_STATUSES = [
        'delivered',
        'received',
        'completed',
    ];

    protected $fillable = [
        'user_id',
        'order_type',
        'custom_order_request_id',
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
        'deleted_at' => 'datetime',
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

    public function customOrderRequest()
    {
        return $this->belongsTo(CustomOrderRequest::class, 'custom_order_request_id');
    }

    public function getOrderTypeLabelAttribute(): string
    {
        return match ($this->order_type) {
            'online_order' => 'Online Order',
            'custom_order' => 'Custom Order',
            'walk_in_order' => 'Walk-in Order',
            default => $this->order_type ? ucfirst(str_replace('_', ' ', $this->order_type)) : 'Online Order',
        };
    }

    public function getComputedSubtotalAttribute()
    {
        return $this->subtotal
            ?? $this->items->sum(fn ($i) => $i->quantity * $i->price);
    }

    public static function normalizeStatus(?string $status): string
    {
        return str_replace([' ', '-'], '_', strtolower(trim((string) $status)));
    }

    public function isHistoryOrder(): bool
    {
        $status = self::normalizeStatus($this->status);
        $paymentStatus = self::normalizeStatus($this->payment_status);

        return in_array($status, self::HISTORY_ORDER_STATUSES, true)
            || ($status === 'delivered' && $paymentStatus === 'paid');
    }

    public function isActiveOrder(): bool
    {
        return ! $this->isHistoryOrder();
    }

    public function isRevenueOrder(): bool
    {
        return self::normalizeStatus($this->payment_status) === 'paid'
            && in_array(self::normalizeStatus($this->status), self::REVENUE_ORDER_STATUSES, true);
    }

    public function scopeHistoryOrders(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->whereIn('status', self::HISTORY_ORDER_STATUSES)
                ->orWhere(function (Builder $query): void {
                    $query->where('status', 'delivered')
                        ->where('payment_status', 'paid');
                });
        });
    }

    public function scopeActiveOrders(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $query): void {
                $query->whereNull('status')
                    ->orWhereNotIn('status', self::HISTORY_ORDER_STATUSES);
            })
            ->where(function (Builder $query): void {
                $query->whereNull('status')
                    ->orWhere('status', '<>', 'delivered')
                    ->orWhere(function (Builder $query): void {
                        $query->where('status', 'delivered')
                            ->where(function (Builder $query): void {
                                $query->whereNull('payment_status')
                                    ->orWhere('payment_status', '<>', 'paid');
                            });
                    });
            });
    }

    public function scopeRevenueOrders(Builder $query): Builder
    {
        return $query
            ->where('payment_status', 'paid')
            ->whereIn('status', self::REVENUE_ORDER_STATUSES);
    }
}
