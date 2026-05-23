<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public static function current(bool $create = true): ?self
    {
        if (! auth()->check()) {
            return null;
        }

        return $create
            ? static::firstOrCreate(['user_id' => auth()->id()])
            : static::where('user_id', auth()->id())->first();
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
