<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Crafted Pieces Order Receipt'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-receipt',
            with: [
                'order' => $this->order
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}