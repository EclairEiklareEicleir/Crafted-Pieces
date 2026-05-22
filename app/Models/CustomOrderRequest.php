<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CustomOrderRequest extends Model
{
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

        'estimated_price',
        'final_price',
        'admin_notes',

        'payment_status',
        'payment_method',
        'paymongo_checkout_id',
        'paymongo_payment_id',

        'status',
        'quoted_at',
        'paid_at',

        // ✅ ADD THIS FOR PAYMENT DEADLINE SYSTEM
        'payment_due_at',
    ];

    protected $casts = [
        'quoted_at' => 'datetime',
        'paid_at' => 'datetime',
        'payment_due_at' => 'datetime',
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
}