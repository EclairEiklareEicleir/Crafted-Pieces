<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // LIST + FILTER + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = Order::query()->latest();

        // SEARCH (name/email/id)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // SHOW SINGLE ORDER
    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    // UPDATE SINGLE STATUS
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated.');
    }

    // DELETE SINGLE
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted.');
    }

    // BULK ACTIONS
    public function bulkAction(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'action' => 'required|string'
        ]);

        $orders = Order::whereIn('id', $request->orders);

        switch ($request->action) {

            case 'delete':
                $orders->delete();
                break;

            case 'shipped':
                $orders->update(['status' => 'shipped']);
                break;

            case 'delivered':
                $orders->update(['status' => 'delivered']);
                break;

            case 'received':
                $orders->update(['status' => 'received']);
                break;

            default:
                abort(400, 'Invalid bulk action');
        }

        return back()->with('success', 'Bulk action completed.');
    }

    // MANUAL ORDER INSERT (PROF REQUIREMENT)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Order::create($validated + [
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        return back()->with('success', 'Manual order created successfully.');
    }
    public function create()
    {
        return view('admin.orders.create');
    }
}