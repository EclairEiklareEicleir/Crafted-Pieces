<?php

namespace App\Models;

use App\Services\PricingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class CustomOrderRequest extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'item_type',
        'design_theme',
        'preferred_size',
        'description',
        'reference_image_path',

        'estimated_price',
        'final_price',
        'admin_notes',

        'payment_status',
        'payment_method',
        'paymongo_checkout_id',
        'paymongo_payment_id',

        'status',
    'quote_status',
        'quoted_at',
        'paid_at',

        // ✅ ADD THIS FOR PAYMENT DEADLINE SYSTEM
        'payment_due_at',
    ];

    protected $casts = [
        'quoted_at' => 'datetime',
        'paid_at' => 'datetime',
        'payment_due_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANTS (SOURCE OF TRUTH)
    |--------------------------------------------------------------------------
    */
    const STATUS_PENDING = 'pending';
    const STATUS_QUOTED = 'quoted';
    const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    const STATUS_PAID = 'paid';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    const QUOTE_STATUS_PENDING = 'pending';
    const QUOTE_STATUS_QUOTED = 'quoted';
    const QUOTE_STATUS_ACCEPTED = 'accepted';
    const QUOTE_STATUS_DECLINED = 'declined';

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(CustomOrderMessage::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'custom_order_request_id');
    }

    public function syncLinkedOrder(): ?Order
    {
        $syncableStatuses = [
            self::STATUS_AWAITING_PAYMENT,
            self::STATUS_PAID,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
        ];

        if (! in_array($this->status, $syncableStatuses, true)) {
            $linkedOrder = $this->order()->first();

            if ($linkedOrder && (in_array($this->quote_status, [self::QUOTE_STATUS_DECLINED], true) || $this->status === self::STATUS_REJECTED)) {
                $linkedOrder->update([
                    'status' => 'cancelled',
                    'payment_status' => $this->payment_status === 'paid' ? 'paid' : 'cancelled',
                ]);
            }

            return $linkedOrder;
        }

        $basePrice = (float) ($this->final_price ?? $this->estimated_price ?? 0);

        if ($basePrice <= 0) {
            return null;
        }

        $pricing = app(PricingService::class)->calculateCustomOrder($basePrice);

        $orderStatus = match ($this->status) {
            self::STATUS_PAID => 'processing',
            self::STATUS_IN_PROGRESS => 'processing',
            self::STATUS_COMPLETED => 'completed',
            default => 'awaiting_payment',
        };

        return $this->order()->updateOrCreate(
            ['custom_order_request_id' => $this->id],
            [
                'user_id' => $this->user_id,
                'guest_session_id' => null,
                'order_type' => 'custom_order',
                'full_name' => $this->name,
                'email' => $this->email,
                'shipping_address' => 'Custom order details are handled in the custom order thread.',
                'payment_method' => $this->payment_method ?: 'PayMongo',
                'payment_status' => $this->payment_status === 'paid' ? 'paid' : ($this->payment_status ?: 'pending'),
                'paymongo_checkout_id' => $this->paymongo_checkout_id,
                'paymongo_payment_id' => $this->paymongo_payment_id,
                'paid_at' => $this->paid_at,
                'subtotal' => $pricing['base_price'],
                'platform_fee' => $pricing['platform_fee'],
                'delivery_fee' => $pricing['delivery_fee'],
                'vat_amount' => $pricing['vat'],
                'total_amount' => $pricing['total'],
                'status' => $orderStatus,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECKS
    |--------------------------------------------------------------------------
    */

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isQuoted()
    {
        return $this->status === self::STATUS_QUOTED;
    }

    public function isAwaitingPayment()
    {
        return $this->status === self::STATUS_AWAITING_PAYMENT;
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isActive()
    {
        return !in_array($this->status, [
            self::STATUS_REJECTED,
            self::STATUS_COMPLETED,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HELPERS (NEW)
    |--------------------------------------------------------------------------
    */

    public function paymentIsExpired(): bool
    {
        return $this->payment_due_at
            ? Carbon::now()->greaterThan(Carbon::parse($this->payment_due_at))
            : false;
    }

    public function paymentIsValid(): bool
    {
        return $this->canPayWithPayMongo();
    }

    public function canPayWithPayMongo(): bool
    {
        return $this->status === self::STATUS_AWAITING_PAYMENT
            && (float) ($this->final_price ?? 0) > 0
            && $this->payment_status !== 'paid'
            && ! empty($this->payment_due_at)
            && ! $this->paymentIsExpired();
    }

    /*
    |--------------------------------------------------------------------------
    | UI STATUS LABEL
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Discussion',
            self::STATUS_QUOTED => 'Quoted',
            self::STATUS_AWAITING_PAYMENT => 'Awaiting Payment',
            self::STATUS_PAID => 'Paid',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    public function getQuoteStatusLabelAttribute()
    {
        return match ($this->quote_status) {
            self::QUOTE_STATUS_PENDING => 'Pending',
            self::QUOTE_STATUS_QUOTED => 'Price Quoted',
            self::QUOTE_STATUS_ACCEPTED => 'Quote Accepted',
            self::QUOTE_STATUS_DECLINED => 'Quote Declined',
            default => $this->quote_status ? ucfirst($this->quote_status) : 'Pending',
        };
    }

    public function getReferenceImageUrlAttribute(): ?string
    {
        if (! $this->reference_image_path) {
            return null;
        }

        $path = ltrim($this->reference_image_path, '/');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (! str_contains($path, '/')) {
            $path = 'custom-orders/reference-images/' . $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
