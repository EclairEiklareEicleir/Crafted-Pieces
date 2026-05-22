<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayMongoController extends Controller
{
    public function success(Request $request, Order $order, PayMongoService $payMongoService)
    {
        if ($order->payment_method !== 'PayMongo') {
            abort(404);
        }

        if ($order->payment_status !== 'paid' && $order->paymongo_checkout_id) {
            try {
                $checkoutSession = $payMongoService->retrieveCheckoutSession($order->paymongo_checkout_id);

                if ($payMongoService->isPaid($checkoutSession)) {
                    $order->update([
                        'payment_status' => 'paid',
                        'paymongo_payment_id' => $payMongoService->extractPaymentId($checkoutSession),
                        'paid_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('user.paymongo-status', [
            'order' => $order->fresh(),
            'title' => $order->fresh()->payment_status === 'paid' ? 'Payment Confirmed' : 'Payment Pending',
            'message' => $order->fresh()->payment_status === 'paid'
                ? 'Your PayMongo payment has been confirmed.'
                : 'We are waiting for PayMongo to confirm your payment. This page will update once the webhook or session verification completes.',
        ]);
    }

    public function cancel(Order $order)
    {
        if ($order->payment_method !== 'PayMongo') {
            abort(404);
        }

        return view('user.paymongo-status', [
            'order' => $order,
            'title' => 'Payment Cancelled',
            'message' => 'Your PayMongo checkout was cancelled. The order remains pending so you can try again.',
        ]);
    }

    public function webhook(Request $request, PayMongoService $payMongoService)
    {
        $rawPayload = $request->getContent();
        $payload = json_decode($rawPayload, true) ?: [];

        if (! $this->isValidSignature($request, $rawPayload)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $eventType = $this->extractEventType($payload);

        if ($eventType !== 'checkout_session.payment.paid') {
            return response()->json(['message' => 'Ignored.'], 200);
        }

        $payableType = data_get($payload, 'data.attributes.metadata.payable_type');

        $payable = match ($payableType) {
            'custom_order' => $this->resolveCustomOrderFromPayload($payload),
            'order' => $this->resolveOrderFromPayload($payload),
            default => $this->resolveOrderFromPayload($payload) ?? $this->resolveCustomOrderFromPayload($payload),
        };

        if (! $payable) {
            return response()->json(['message' => 'Payable record not found.'], 404);
        }

        $checkoutSessionId = data_get($payload, 'data.id')
            ?? data_get($payload, 'data.attributes.checkout_session_id')
            ?? $payable->paymongo_checkout_id;

        try {
            $checkoutSession = $checkoutSessionId
                ? $payMongoService->retrieveCheckoutSession($checkoutSessionId)
                : $payload;

            if (! $payMongoService->isPaid($checkoutSession)) {
                return response()->json(['message' => 'Checkout session not paid yet.'], 200);
            }

            if ($payable instanceof Order) {
                $payable->update([
                    'payment_status' => 'paid',
                    'paymongo_checkout_id' => $checkoutSessionId ?: $payable->paymongo_checkout_id,
                    'paymongo_payment_id' => $payMongoService->extractPaymentId($checkoutSession),
                    'paid_at' => now(),
                ]);

                return response()->json(['message' => 'Order marked as paid.'], 200);
            }

            if ($payable instanceof CustomOrderRequest) {
                $payable->update([
                    'payment_method' => 'PayMongo',
                    'payment_status' => 'paid',
                    'paymongo_checkout_id' => $checkoutSessionId ?: $payable->paymongo_checkout_id,
                    'paymongo_payment_id' => $payMongoService->extractPaymentId($checkoutSession),
                    'paid_at' => now(),
                    'status' => CustomOrderRequest::STATUS_PAID,
                ]);

                return response()->json(['message' => 'Custom order marked as paid.'], 200);
            }

            return response()->json(['message' => 'Unsupported payable record.'], 422);
        } catch (\Throwable $e) {
            Log::error('PayMongo webhook processing failed.', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Webhook processing failed.'], 500);
        }
    }

    private function resolveOrderFromPayload(array $payload): ?Order
    {
        $orderId = data_get($payload, 'data.attributes.metadata.order_id')
            ?? data_get($payload, 'data.attributes.reference_number')
            ?? data_get($payload, 'data.attributes.data.metadata.order_id');

        if (is_string($orderId) && str_starts_with($orderId, 'ORDER-')) {
            $orderId = (int) substr($orderId, 6);
        }

        if (! is_numeric($orderId)) {
            return null;
        }

        return Order::find((int) $orderId);
    }

    private function resolveCustomOrderFromPayload(array $payload): ?CustomOrderRequest
    {
        $customOrderId = data_get($payload, 'data.attributes.metadata.custom_order_id')
            ?? data_get($payload, 'data.attributes.reference_number')
            ?? data_get($payload, 'data.attributes.data.metadata.custom_order_id');

        if (is_string($customOrderId) && str_starts_with($customOrderId, 'CUSTOM-')) {
            $customOrderId = (int) substr($customOrderId, 7);
        }

        if (! is_numeric($customOrderId)) {
            return null;
        }

        return CustomOrderRequest::find((int) $customOrderId);
    }

    private function extractEventType(array $payload): ?string
    {
        return data_get($payload, 'data.attributes.type')
            ?? data_get($payload, 'data.type')
            ?? data_get($payload, 'event');
    }

    private function isValidSignature(Request $request, string $rawPayload): bool
    {
        $secret = (string) config('services.paymongo.webhook_secret');

        if ($secret === '') {
            return true;
        }

        $signatureHeader = (string) ($request->header('Paymongo-Signature') ?? $request->header('X-Paymongo-Signature') ?? '');

        if ($signatureHeader === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $rawPayload, $secret);

        if (hash_equals($expected, $signatureHeader)) {
            return true;
        }

        foreach (explode(',', $signatureHeader) as $part) {
            $part = trim($part);

            if ($part === $expected) {
                return true;
            }

            if (str_contains($part, '=')) {
                [, $value] = array_pad(explode('=', $part, 2), 2, null);

                if (is_string($value) && hash_equals($expected, trim($value))) {
                    return true;
                }
            }
        }

        return false;
    }
}