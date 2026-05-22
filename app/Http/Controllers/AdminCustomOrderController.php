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

class AdminCustomOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST ALL CUSTOM REQUESTS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $requests = CustomOrderRequest::latest()->get();

        return view('admin.custom.index', compact('requests'));
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

        Notification::create([
            'user_id' => $customOrder->user_id,
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

            // quotation stage
            'status' => CustomOrderRequest::STATUS_QUOTED,
        ]);

        Notification::create([
            'user_id' => $customOrder->user_id,
            'title' => 'Quotation Received',
            'message' => 'Your custom order has been quoted and awaiting payment.',
            'link' => route('custom-order.show', $customOrder),
        ]);

        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' => 'Quotation sent: PHP ' .
                number_format($validated['final_price'], 2),
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

            // payment deadline (3 days)
            'payment_due_at' => now()->addDays(3),

            'payment_status' => $customOrder->payment_status === 'paid'
                ? CustomOrderRequest::STATUS_PAID
                : ($customOrder->payment_status ?: 'unpaid'),
        ]);

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
            'status' => CustomOrderRequest::STATUS_REJECTED
        ]);

        Notification::create([
            'user_id' => $customOrder->user_id,
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
}