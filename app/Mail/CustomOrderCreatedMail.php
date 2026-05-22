<?php

namespace App\Mail;

use App\Models\CustomOrderRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomOrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public CustomOrderRequest $order;

    public function __construct(CustomOrderRequest $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this
            ->subject('Custom Order Request Submitted')
            ->view('emails.custom-order-receipt');
    }
}