<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderMessage;
use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomOrderQuotationMail;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminCustomOrderController extends Controller
{
    private const ACTIVE_STATUSES = [
        CustomOrderRequest::STATUS_PENDING,
        CustomOrderRequest::STATUS_QUOTED,
        CustomOrderRequest::STATUS_AWAITING_PAYMENT,
        CustomOrderRequest::STATUS_PAID,
        CustomOrderRequest::STATUS_IN_PROGRESS,
    ];

    private const RESOLVED_STATUSES = [
        CustomOrderRequest::STATUS_COMPLETED,
        CustomOrderRequest::STATUS_REJECTED,
        'received',
        'cancelled',
        'declined',
        'quote_declined',
        'refunded',
        'resolved',
    ];

    /*
    |--------------------------------------------------------------------------
    | LIST ALL CUSTOM REQUESTS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $activeRequests = CustomOrderRequest::whereNotIn('status', self::RESOLVED_STATUSES)
            ->latest()
            ->get();

        $resolvedRequests = CustomOrderRequest::whereIn('status', self::RESOLVED_STATUSES)
            ->latest()
            ->get();

        return view('admin.custom.index', [
            'activeRequests' => $activeRequests,
            'resolvedRequests' => $resolvedRequests,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE REQUEST
    |--------------------------------------------------------------------------
    */
    public function show(CustomOrderRequest $customOrder)
    {
        $customOrder->load(['messages.user']);

        return view('admin.custom.show', [
            'request' => $customOrder
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN MESSAGE
    |--------------------------------------------------------------------------
    */
    public function message(Request $httpRequest, CustomOrderRequest $customOrder)
    {
        $httpRequest->validate([
            'message' => 'required|string|max:1000'
        ]);

        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' => $httpRequest->message,
        ]);

        Notification::notifyUser($customOrder->user, [
            'title' => 'New Admin Reply',
            'message' => 'Admin replied to your custom order request.',
            'link' => route('custom-order.show', $customOrder),
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | SEND QUOTATION
    |--------------------------------------------------------------------------
    */
    public function quote(Request $httpRequest, CustomOrderRequest $customOrder)
    {
        $validated = $httpRequest->validate([
            'final_price' => 'required|numeric|min:1',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $customOrder->update([
            'final_price' => $validated['final_price'],
            'admin_notes' => $validated['admin_notes'],
            'quoted_at' => now(),
            'quote_status' => CustomOrderRequest::QUOTE_STATUS_QUOTED,

            // quotation stage
            'status' => CustomOrderRequest::STATUS_QUOTED,
        ]);

        Notification::notifyUser($customOrder->user, [
            'title' => 'Quotation Received',
            'message' => 'Your custom order has been quoted and awaiting payment.',
            'link' => route('custom-order.show', $customOrder),
        ]);

        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' => 'The seller has quoted a price for your custom order. Quoted Price: PHP ' .
                number_format($validated['final_price'], 2),
            'message_type' => 'quote',
            'is_system' => true,
            'meta' => [
                'quoted_price' => (float) $validated['final_price'],
            ],
        ]);

        return back()->with('success', 'Quotation sent.');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCEPT REQUEST → MOVE TO PAYMENT STAGE
    |--------------------------------------------------------------------------
    */
    public function accept(CustomOrderRequest $customOrder)
    {
        Log::info('ADMIN ACCEPT HIT', [
            'id' => $customOrder->id,
            'status' => $customOrder->status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ALLOWED ACCEPT STATES
        |--------------------------------------------------------------------------
        */
        if (!in_array($customOrder->status, [
            CustomOrderRequest::STATUS_PENDING,
            CustomOrderRequest::STATUS_QUOTED,
        ])) {

            Log::warning('ACCEPT BLOCKED', [
                'status' => $customOrder->status
            ]);

            abort(403, 'Order cannot be accepted at this stage.');
        }

        if ((float) ($customOrder->final_price ?? 0) <= 0) {
            abort(422, 'Please send a valid quotation before moving this request to payment.');
        }

        /*
        |--------------------------------------------------------------------------
        | MOVE TO PAYMENT STAGE
        |--------------------------------------------------------------------------
        */
        $customOrder->update([

            // waiting for customer payment
            'status' => CustomOrderRequest::STATUS_AWAITING_PAYMENT,
            'quote_status' => CustomOrderRequest::QUOTE_STATUS_ACCEPTED,

            // payment deadline (3 days)
            'payment_due_at' => now()->addDays(3),

            'payment_status' => $customOrder->payment_status === 'paid'
                ? CustomOrderRequest::STATUS_PAID
                : ($customOrder->payment_status ?: 'unpaid'),
        ]);

        $customOrder->syncLinkedOrder();

        Mail::to($customOrder->email)
            ->send(new CustomOrderQuotationMail($customOrder));

        /*
        |--------------------------------------------------------------------------
        | SYSTEM MESSAGE
        |--------------------------------------------------------------------------
        */
        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' =>
                'Your request has been approved. ' .
                'Please complete payment within 3 days.',
            'message_type' => 'system',
            'is_system' => true,
            'meta' => [
                'event' => 'admin_approved',
            ],
        ]);

        Log::info('ORDER MOVED TO PAYMENT STAGE');

        return back()->with(
            'success',
            'Order approved and moved to payment stage.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT REQUEST
    |--------------------------------------------------------------------------
    */
    public function reject(CustomOrderRequest $customOrder)
    {
        if ($customOrder->status === CustomOrderRequest::STATUS_PAID) {
            abort(403, 'Cannot reject paid orders.');
        }

        $customOrder->update([
            'status' => CustomOrderRequest::STATUS_REJECTED,
            'quote_status' => CustomOrderRequest::QUOTE_STATUS_DECLINED,
        ]);

        $customOrder->syncLinkedOrder();

        Notification::notifyUser($customOrder->user, [
            'title' => 'Request Rejected',
            'message' => 'Admin rejected your custom order request.',
            'link' => route('custom-order.show', $customOrder),
        ]);

        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' => 'Your custom order request was rejected.',
        ]);

        return back()->with(
            'success',
            'Order rejected successfully.'
        );
    }

    public function destroy(CustomOrderRequest $customOrder)
    {
        try {
            $customOrder->delete();

            return redirect()
                ->route('admin.custom.index')
                ->with('success', 'Custom order request deleted.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete this custom order request right now.');
        }
    }

    public function downloadReceipt(CustomOrderRequest $customOrder)
    {
        if (! extension_loaded('gd')) {
            Log::error('Admin custom receipt PDF generation failed because the PHP GD extension is missing.', [
                'custom_order_id' => $customOrder->id,
                'php_binary' => PHP_BINARY,
            ]);

            abort(500, 'PDF receipts require the PHP GD extension. Enable extension=gd in C:\\xampp\\php\\php.ini and restart Apache or php artisan serve.');
        }

        $pricing = [
            'base_price' => $customOrder->final_price ?? $customOrder->estimated_price,
            'platform_fee' => 0,
            'delivery_fee' => 0,
            'vat' => 0,
            'total' => $customOrder->final_price ?? $customOrder->estimated_price,
        ];

        $pdf = Pdf::loadView('user.receipt.customreceipt-pdf', [
            'order' => $customOrder,
            'pricing' => $pricing,
        ]);

        return $pdf->download('custom-receipt-' . $customOrder->id . '.pdf');
    }
}
