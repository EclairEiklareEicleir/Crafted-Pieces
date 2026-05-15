<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // =========================
    // MY ORDERS (LOGGED IN)
    // =========================
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    // =========================
    // VIEW SINGLE ORDER (NEW)
    // =========================
    public function show(Order $order)
    {
        // security check (important)
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $order->load('items.product');

        return view('user.orders.show', compact('order'));
    }

    // =========================
    // GUEST TRACK FORM
    // =========================
    public function trackForm()
    {
        return view('user.orders.track');
    }

    // =========================
    // GUEST TRACK RESULT
    // =========================
    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'email' => 'required|email'
        ]);

        $order = Order::with('items.product')
            ->where('id', $request->order_id)
            ->where('email', $request->email)
            ->first();

        if (! $order) {
            return back()->withErrors([
                'track' => 'Order not found. Please check your details.'
            ]);
        }

        return view('user.orders.track-result', compact('order'));
    }
}