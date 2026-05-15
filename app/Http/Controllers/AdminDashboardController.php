<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');

        // =========================
        // FILTER LOGIC
        // =========================

        $ordersQuery = Order::query();
        $customQuery = CustomOrderRequest::query();

        if ($filter === 'month') {

            $ordersQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);

            $customQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);

        } elseif ($filter === 'year') {

            $ordersQuery->whereYear('created_at', now()->year);

            $customQuery->whereYear('created_at', now()->year);
        }

        // =========================
        // STATISTICS
        // =========================

        $totalRevenue = $ordersQuery->sum('total_amount');

        $totalOrders = (clone $ordersQuery)->count();

        $totalCommissions = (clone $customQuery)->count();

        $confirmedCommissions = (clone $customQuery)
            ->where('status', 'confirmed')
            ->count();

        // =========================
        // RECENT ORDERS
        // =========================

        $recentOrders = Order::latest()
            ->take(5)
            ->get();

        // =========================
        // RECENT CUSTOM REQUESTS
        // =========================

        $recentRequests = CustomOrderRequest::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'filter',
            'totalRevenue',
            'totalOrders',
            'totalCommissions',
            'confirmedCommissions',
            'recentOrders',
            'recentRequests'
        ));
    }
}