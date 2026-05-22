<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Services\PayMongoService;
use Illuminate\Http\Request;

class CustomOrderPayMongoController extends Controller
{
    public function checkout(Request $request, CustomOrderRequest $customOrder, PayMongoService $payMongoService)
    {
        if (auth()->check() && $customOrder->user_id !== auth()->id()) {
            abort(403);
        }

        if (! $customOrder->canPayWithPayMongo()) {
            if ($customOrder->paymentIsExpired()) {
                return back()->withErrors([
                    'payment' => 'This payment window is no longer valid.',
                ]);
            }

            if ($customOrder->status !== CustomOrderRequest::STATUS_AWAITING_PAYMENT || (float) ($customOrder->final_price ?? 0) <= 0) {
                return back()->withErrors([
                    'payment' => 'This quotation is not ready for payment yet.',
                ]);
            }

            return back()->withErrors([
                'payment' => 'This payment is not available right now.',
            ]);
        }

        if ($customOrder->payment_status === 'pending' && $customOrder->paymongo_checkout_id) {
            try {
                $existingSession = $payMongoService->retrieveCheckoutSession($customOrder->paymongo_checkout_id);

                if ($payMongoService->isPaid($existingSession)) {
                    $customOrder->update([
                        'payment_status' => 'paid',
                        'paymongo_payment_id' => $payMongoService->extractPaymentId($existingSession),
                        'paid_at' => now(),
                        'status' => CustomOrderRequest::STATUS_PAID,
                        'payment_method' => 'PayMongo',
                    ]);

                    return redirect()->route('custom-order.paymongo.success', $customOrder);
                }

                $existingCheckoutUrl = data_get($existingSession, 'data.attributes.checkout_url');

                if ($existingCheckoutUrl) {
                    return redirect()->away($existingCheckoutUrl);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        try {
            $session = $payMongoService->createCustomOrderCheckoutSession($customOrder);

            $customOrder->update([
                'payment_method' => 'PayMongo',
                'payment_status' => 'pending',
                'paymongo_checkout_id' => $session['checkout_session_id'],
            ]);

            return redirect()->away($session['checkout_url']);
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'payment' => 'Unable to start PayMongo checkout right now. Please try again.',
            ]);
        }
    }

    public function success(Request $request, CustomOrderRequest $customOrder, PayMongoService $payMongoService)
    {
        if (auth()->check() && $customOrder->user_id !== auth()->id()) {
            abort(403);
        }

        if ($customOrder->paymongo_checkout_id && $customOrder->payment_status !== 'paid') {
            try {
                $checkoutSession = $payMongoService->retrieveCheckoutSession($customOrder->paymongo_checkout_id);

                if ($payMongoService->isPaid($checkoutSession)) {
                    $customOrder->update([
                        'payment_status' => 'paid',
                        'paymongo_payment_id' => $payMongoService->extractPaymentId($checkoutSession),
                        'paid_at' => now(),
                        'status' => CustomOrderRequest::STATUS_PAID,
                        'payment_method' => 'PayMongo',
                    ]);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('user.custom-order.paymongo-status', [
            'order' => $customOrder->fresh(),
            'title' => $customOrder->fresh()->payment_status === 'paid' ? 'Payment Confirmed' : 'Payment Pending',
            'message' => $customOrder->fresh()->payment_status === 'paid'
                ? 'Your PayMongo payment for this quotation has been confirmed.'
                : 'We are waiting for PayMongo to confirm your custom order payment.',
        ]);
    }

    public function cancel(Request $request, CustomOrderRequest $customOrder)
    {
        if (auth()->check() && $customOrder->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.custom-order.paymongo-status', [
            'order' => $customOrder,
            'title' => 'Payment Cancelled',
            'message' => 'Your PayMongo checkout was cancelled. The quotation remains open while the payment window is valid.',
        ]);
    }
}