<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderMessage;
use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // quotation stage
            'status' => CustomOrderRequest::STATUS_QUOTED,
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
        logger()->info('ADMIN ACCEPT HIT', [
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

            logger()->warning('ACCEPT BLOCKED', [
                'status' => $customOrder->status
            ]);

            abort(403, 'Order cannot be accepted at this stage.');
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
        ]);

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

        logger()->info('ORDER MOVED TO PAYMENT STAGE');

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