<?php

namespace App\Console\Commands;

use App\Models\CustomOrderRequest;
use Illuminate\Console\Command;

class ExpireCustomOrders extends Command
{
    protected $signature = 'orders:expire-custom';

    protected $description = 'Expire unpaid custom orders';

    public function handle()
    {
        $expiredOrders = CustomOrderRequest::where(
            'status',
            CustomOrderRequest::STATUS_AWAITING_PAYMENT
        )
        ->whereNotNull('payment_due_at')
        ->where('payment_due_at', '<', now())
        ->get();

        /** @var CustomOrderRequest $order */
        foreach ($expiredOrders as $order) {

            $order->update([
                'status' => CustomOrderRequest::STATUS_REJECTED
            ]);

            $this->info("Expired order #{$order->id}");
        }

        $this->info('Expired order scan completed.');

        return self::SUCCESS;
    }
}