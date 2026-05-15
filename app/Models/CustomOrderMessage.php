<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrderMessage extends Model
{
    protected $fillable = [
        'custom_order_request_id',
        'user_id',
        'message',
    ];

    public function customOrderRequest()
    {
        return $this->belongsTo(CustomOrderRequest::class, 'custom_order_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}