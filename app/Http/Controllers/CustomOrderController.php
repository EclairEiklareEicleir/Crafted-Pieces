<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderMessage;
use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST USER ORDERS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $orders = CustomOrderRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.custom-order.index', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE ORDER (TICKET VIEW)
    |--------------------------------------------------------------------------
    */
    public function show(CustomOrderRequest $customOrder)
    {
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $customOrder->load(['messages.user']);

        return view('user.custom-order.show', [
            'order' => $customOrder
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHAT SYSTEM
    |--------------------------------------------------------------------------
    */
    public function message(Request $request, CustomOrderRequest $customOrder)
    {
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        CustomOrderMessage::create([
            'custom_order_request_id' => $customOrder->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE CUSTOM ORDER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'item_type' => 'required|string|max:255',
            'design_theme' => 'nullable|string|max:255',
            'preferred_size' => 'nullable|string|max:50',
            'description' => 'required|string',
        ]);

        $estimate = $this->calculateEstimate($validated);

        $order = CustomOrderRequest::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'item_type' => $validated['item_type'],
            'design_theme' => $validated['design_theme'],
            'preferred_size' => $validated['preferred_size'],
            'description' => $validated['description'],
            'estimated_price' => $estimate,
            'status' => CustomOrderRequest::STATUS_PENDING,
        ]);

        return redirect()->route('custom-order.show', $order);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT CONFIRMATION (USER)
    |--------------------------------------------------------------------------
    */
    public function pay(Request $request, CustomOrderRequest $customOrder)
    {
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        if ($customOrder->status !== CustomOrderRequest::STATUS_AWAITING_PAYMENT) {
            abort(403, 'Order not payable.');
        }

        $customOrder->update([
            'status' => CustomOrderRequest::STATUS_PAID
        ]);

        return redirect()
            ->route('custom-order.show', $customOrder)
            ->with('success', 'Payment successful. Order confirmed.');
    }

    /*
    |--------------------------------------------------------------------------
    | ESTIMATE ENGINE
    |--------------------------------------------------------------------------
    */
    private function calculateEstimate($data)
    {
        $baseMaterial = 100;
        $laborMultiplier = 1.5;

        $sizeMultiplier = match ($data['preferred_size'] ?? '') {
            '10cm' => 0.8,
            '20cm' => 1.0,
            '30cm' => 1.2,
            '40cm' => 1.5,
            default => 1.8,
        };

        $text = strtolower(
            ($data['item_type'] ?? '') . ' ' . ($data['description'] ?? '')
        );

        $complexityMultiplier = 1.0;

        if (str_contains($text, 'detailed')) $complexityMultiplier += 0.2;
        if (str_contains($text, 'complex')) $complexityMultiplier += 0.4;
        if (str_contains($text, 'large')) $complexityMultiplier += 0.3;

        return $baseMaterial * $laborMultiplier * $sizeMultiplier * $complexityMultiplier;
    }
}