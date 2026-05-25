<?php

namespace App\Services;

use App\Models\Setting;

class PricingService
{
    /*
    |--------------------------------------------------------------------------
    | LOAD SETTINGS
    |--------------------------------------------------------------------------
    */
    private function getRate(string $key, float $default): float
    {
        $setting = Setting::where('key', $key)->first();

        return $setting ? (float) $setting->value : $default;
    }

    /*
    |--------------------------------------------------------------------------
    | CART CALCULATION
    |--------------------------------------------------------------------------
    */
    public function calculate($items): array
    {
        $subtotal = $this->calculateSubtotal($items);

        $platformRate = $this->getRate('platform_fee_rate', 0.05);
        $vatRate = $this->getRate('vat_rate', 0.12);
        $deliveryFee = $this->getRate('delivery_fee', 0);

        $platformFee = $subtotal * $platformRate;
        $vat = $subtotal * $vatRate;

        $total = $subtotal + $platformFee + $vat + $deliveryFee;

        return [
            'subtotal' => round($subtotal, 2),
            'platform_fee' => round($platformFee, 2),
            'vat' => round($vat, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER REBUILD (RECEIPT)
    |--------------------------------------------------------------------------
    */
    public function calculateFromOrder($order): array
    {
        $items = $order->items ?? collect();

        $subtotal = $items->sum(fn ($item) =>
            $item->quantity * $item->price
        );

        $platformRate = $this->getRate('platform_fee_rate', 0.05);
        $vatRate = $this->getRate('vat_rate', 0.12);
        $deliveryFee = $order->delivery_fee ?? $this->getRate('delivery_fee', 0);

        $platformFee = $subtotal * $platformRate;
        $vat = $subtotal * $vatRate;

        $total = $subtotal + $platformFee + $vat + $deliveryFee;

        return [
            'subtotal' => round($subtotal, 2),
            'platform_fee' => round($platformFee, 2),
            'vat' => round($vat, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM ORDER CALCULATION (NEW)
    |--------------------------------------------------------------------------
    */
    public function calculateCustomOrder(float $basePrice): array
    {
        $platformRate = $this->getRate('platform_fee_rate', 0.05);
        $vatRate = $this->getRate('vat_rate', 0.12);
        $deliveryFee = $this->getRate('delivery_fee', 0);

        $platformFee = $basePrice * $platformRate;
        $vat = $basePrice * $vatRate;

        $total = $basePrice + $platformFee + $vat + $deliveryFee;

        return [
            'base_price' => round($basePrice, 2),
            'platform_fee' => round($platformFee, 2),
            'vat' => round($vat, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'total' => round($total, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | INTERNAL
    |--------------------------------------------------------------------------
    */
    private function calculateSubtotal($items): float
    {
        return $items->sum(fn ($item) =>
            $item->quantity * $item->price
        );
    }
}