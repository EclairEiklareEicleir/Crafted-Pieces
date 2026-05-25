<?php

namespace App\Services;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PayMongoService
{
    private string $baseUrl = 'https://api.paymongo.com/v1';

    public function createCheckoutSession(Order $order): array
    {
        return $this->createOrderCheckoutSession($order);
    }

    public function createOrderCheckoutSession(Order $order): array
    {
        $order->loadMissing('items.product');

        return $this->createSession([
            'description' => 'Order #' . $order->id,
            'reference_number' => 'ORDER-' . $order->id,
            'success_url' => URL::signedRoute('checkout.paymongo.success', ['order' => $order]),
            'cancel_url' => URL::signedRoute('checkout.paymongo.cancel', ['order' => $order]),
            'billing' => [
                'name' => $order->full_name,
                'email' => $order->email,
            ],
            'line_items' => $this->buildOrderLineItems($order),
            'metadata' => [
                'payable_type' => 'order',
                'order_id' => (string) $order->id,
            ],
        ]);
    }

    public function createCustomOrderCheckoutSession(CustomOrderRequest $customOrder): array
    {
        return $this->createSession([
            'description' => 'Custom Order #' . $customOrder->id . ' - ' . $customOrder->item_type,
            'reference_number' => 'CUSTOM-' . $customOrder->id,
            'success_url' => URL::signedRoute('custom-order.paymongo.success', ['customOrder' => $customOrder]),
            'cancel_url' => URL::signedRoute('custom-order.paymongo.cancel', ['customOrder' => $customOrder]),
            'billing' => [
                'name' => $customOrder->name,
                'email' => $customOrder->email,
            ],
            'line_items' => [[
                'currency' => 'PHP',
                'amount' => $this->toCentavos((float) $customOrder->final_price),
                'name' => $customOrder->item_type,
                'quantity' => 1,
                'description' => 'Custom order quotation',
            ]],
            'metadata' => [
                'payable_type' => 'custom_order',
                'custom_order_id' => (string) $customOrder->id,
            ],
        ]);
    }

    public function retrieveCheckoutSession(string $checkoutSessionId): array
    {

            Log::info('Retrieving PayMongo checkout session', [
            'checkout_session_id' => $checkoutSessionId,
            'base_url' => $this->baseUrl,
                'env_key_partial' => substr((string) config('services.paymongo.secret_key'), 0, 5) . '...',
        ]);
        $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
            ->acceptJson()
            ->get($this->baseUrl . '/checkout_sessions/' . $checkoutSessionId)
            ->throw();

        return $response->json();
    }

    public function extractPaymentId(array $payload): ?string
    {
        return data_get($payload, 'data.attributes.payments.data.0.id')
            ?? data_get($payload, 'data.attributes.payment_intent.data.id')
            ?? data_get($payload, 'data.attributes.payment_intent.id')
            ?? null;
    }

    public function isPaid(array $payload): bool
    {
        $status = strtolower((string) data_get($payload, 'data.attributes.status', ''));

        if (in_array($status, ['paid', 'succeeded', 'successful'], true)) {
            return true;
        }

        $paymentIntentStatus = strtolower((string) data_get($payload, 'data.attributes.payment_intent.attributes.status', ''));

        if (in_array($paymentIntentStatus, ['paid', 'succeeded', 'successful'], true)) {
            return true;
        }

        $paymentStatus = strtolower((string) data_get($payload, 'data.attributes.payments.data.0.attributes.status', ''));

        return in_array($paymentStatus, ['paid', 'succeeded', 'successful'], true);
    }

    private function createSession(array $attributes): array
    {
        $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl . '/checkout_sessions', [
                'data' => [
                    'attributes' => array_merge([
                        'payment_method_types' => ['card', 'gcash', 'qrph'],
                        'send_email_receipt' => true,
                        'show_description' => true,
                        'show_line_items' => true,
                    ], $attributes),
                ],
            ])
            ->throw();

        $data = $response->json('data', []);
        $responseAttributes = $data['attributes'] ?? [];

        return [
            'checkout_session_id' => data_get($data, 'id') ?? data_get($responseAttributes, 'id') ?? '',
            'checkout_url' => data_get($responseAttributes, 'checkout_url') ?? '',
            'raw' => $response->json(),
        ];
    }

    private function buildOrderLineItems(Order $order): array
    {
        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => $this->toCentavos($item->price),
                'name' => $item->product?->name ?? 'Order Item',
                'quantity' => $item->quantity,
                'description' => $item->product?->description ?? 'Product purchase',
            ];
        }

        $fees = [
            'Platform Fee' => $order->platform_fee,
            'Delivery Fee' => $order->delivery_fee,
            'VAT' => $order->vat_amount,
        ];

        foreach ($fees as $label => $amount) {
            if ((float) $amount <= 0) {
                continue;
            }

            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => $this->toCentavos($amount),
                'name' => $label,
                'quantity' => 1,
                'description' => $label,
            ];
        }

        return $lineItems;
    }

    private function toCentavos(float|int|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}