<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    private const FILTERS = ['today', 'week', 'month', 'year', 'all'];

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');

        if (! in_array($filter, self::FILTERS, true)) {
            $filter = 'month';
        }

        $period = $this->periodForFilter($filter);

        $ordersQuery = $this->applyPeriod(Order::query(), $period);
        $customQuery = $this->applyPeriod(CustomOrderRequest::query(), $period);
        $revenueOrdersQuery = $this->applyPeriod(Order::query()->revenueOrders(), $period);

        $totalRevenue = (clone $revenueOrdersQuery)->sum('total_amount');
        $totalOrders = (clone $ordersQuery)->count();
        $pendingOrders = (clone $ordersQuery)->whereIn('status', ['pending', 'awaiting_payment'])->count();
        $paidOrders = (clone $ordersQuery)->where('payment_status', 'paid')->count();
        $completedOrders = (clone $ordersQuery)->whereIn('status', Order::REVENUE_ORDER_STATUSES)->count();
        $cancelledOrders = (clone $ordersQuery)->whereIn('status', ['cancelled', 'rejected', 'failed', 'refunded'])->count();
        $totalCommissions = (clone $customQuery)->count();
        $activeCommissions = (clone $customQuery)->whereNotIn('status', [
            CustomOrderRequest::STATUS_COMPLETED,
            CustomOrderRequest::STATUS_REJECTED,
        ])->count();
        $resolvedCommissions = (clone $customQuery)->whereIn('status', [
            CustomOrderRequest::STATUS_COMPLETED,
            CustomOrderRequest::STATUS_REJECTED,
        ])->count();

        $salesToday = Order::query()
            ->revenueOrders()
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');

        $salesThisMonth = Order::query()
            ->revenueOrders()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_amount');

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $lowStockVariants = ProductVariant::where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStockVariants = ProductVariant::where('stock', '<=', 0)->count();

        $revenueOrders = (clone $revenueOrdersQuery)
            ->with(['items.product', 'items.productVariant', 'user'])
            ->latest()
            ->get();

        $bestSellingProducts = $this->bestSellingProducts($revenueOrders);
        $bestSellingVariants = $this->bestSellingVariants($revenueOrders);
        $topCustomer = $this->topCustomer($revenueOrders);

        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        $recentRequests = CustomOrderRequest::latest()
            ->take(5)
            ->get();

        $chartData = [
            'salesOverview' => $this->salesOverviewData($revenueOrders, $filter, $period),
            'orderStatuses' => $this->countByField((clone $ordersQuery)->get(), 'status', 'No Status'),
            'paymentStatuses' => $this->countByField((clone $ordersQuery)->get(), 'payment_status', 'Unpaid'),
            'orderTypes' => $this->orderTypeBreakdown((clone $ordersQuery)->get()),
            'bestSellingProducts' => [
                'labels' => $bestSellingProducts->pluck('name')->values(),
                'values' => $bestSellingProducts->pluck('quantity')->values(),
            ],
            'bestSellingVariants' => [
                'labels' => $bestSellingVariants->pluck('name')->values(),
                'values' => $bestSellingVariants->pluck('quantity')->values(),
            ],
        ];

        return view('admin.dashboard', compact(
            'filter',
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'paidOrders',
            'completedOrders',
            'cancelledOrders',
            'totalCommissions',
            'activeCommissions',
            'resolvedCommissions',
            'salesToday',
            'salesThisMonth',
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockVariants',
            'outOfStockVariants',
            'bestSellingProducts',
            'bestSellingVariants',
            'topCustomer',
            'recentOrders',
            'recentRequests',
            'chartData'
        ));
    }

    private function periodForFilter(string $filter): ?array
    {
        return match ($filter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => null,
        };
    }

    private function applyPeriod(Builder $query, ?array $period): Builder
    {
        if ($period === null) {
            return $query;
        }

        return $query->whereBetween('created_at', $period);
    }

    private function countByField(Collection $records, string $field, string $fallbackLabel): array
    {
        $counts = $records
            ->groupBy(fn ($record) => $this->readableLabel($record->{$field} ?: $fallbackLabel))
            ->map->count()
            ->sortDesc();

        return [
            'labels' => $counts->keys()->values(),
            'values' => $counts->values(),
        ];
    }

    private function orderTypeBreakdown(Collection $orders): array
    {
        $counts = $orders
            ->groupBy(fn (Order $order) => $order->order_type_label)
            ->map->count()
            ->sortDesc();

        return [
            'labels' => $counts->keys()->values(),
            'values' => $counts->values(),
        ];
    }

    private function salesOverviewData(Collection $orders, string $filter, ?array $period): array
    {
        if ($filter === 'today') {
            $labels = collect(range(0, 23))->map(fn ($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00');
            $values = $labels->map(function (string $label) use ($orders) {
                $hour = (int) Str::before($label, ':');

                return round((float) $orders
                    ->filter(fn (Order $order) => (int) $order->created_at->format('G') === $hour)
                    ->sum('total_amount'), 2);
            });

            return ['labels' => $labels, 'values' => $values];
        }

        if (in_array($filter, ['week', 'month'], true) && $period !== null) {
            $labels = collect(CarbonPeriod::create($period[0]->copy()->startOfDay(), $period[1]->copy()->startOfDay()))
                ->map(fn (Carbon $date) => $date->format($filter === 'week' ? 'D M j' : 'M j'));

            $values = $labels->map(function (string $label) use ($orders, $filter) {
                return round((float) $orders
                    ->filter(fn (Order $order) => $order->created_at->format($filter === 'week' ? 'D M j' : 'M j') === $label)
                    ->sum('total_amount'), 2);
            });

            return ['labels' => $labels, 'values' => $values];
        }

        $monthSource = $filter === 'year'
            ? collect(range(1, 12))->map(fn ($month) => now()->startOfYear()->month($month))
            : collect(range(11, 0))->map(fn ($offset) => now()->startOfMonth()->subMonths($offset));

        $labels = $monthSource->map(fn (Carbon $month) => $month->format('M Y'));
        $values = $labels->map(function (string $label) use ($orders) {
            return round((float) $orders
                ->filter(fn (Order $order) => $order->created_at->format('M Y') === $label)
                ->sum('total_amount'), 2);
        });

        return ['labels' => $labels->values(), 'values' => $values->values()];
    }

    private function bestSellingProducts(Collection $orders): Collection
    {
        return $orders
            ->flatMap->items
            ->filter(fn (OrderItem $item) => $item->product_id)
            ->groupBy('product_id')
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'name' => $first->product?->name ?? ('Product #' . $first->product_id),
                    'quantity' => (int) $items->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
    }

    private function bestSellingVariants(Collection $orders): Collection
    {
        return $orders
            ->flatMap->items
            ->filter(fn (OrderItem $item) => $item->product_variant_id || $item->variant_name)
            ->groupBy(fn (OrderItem $item) => $item->product_variant_id ?: 'snapshot:' . $item->variant_name)
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'name' => $first->productVariant?->name
                        ?? $first->variant_name
                        ?? ('Variant #' . $first->product_variant_id),
                    'quantity' => (int) $items->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
    }

    private function topCustomer(Collection $orders): ?array
    {
        return $orders
            ->groupBy(fn (Order $order) => $order->email ?: ('user:' . $order->user_id))
            ->map(function (Collection $customerOrders) {
                $first = $customerOrders->first();

                return [
                    'name' => $first->full_name ?: ($first->user?->name ?? 'Unknown customer'),
                    'orders' => $customerOrders->count(),
                    'total' => (float) $customerOrders->sum('total_amount'),
                ];
            })
            ->sortByDesc('total')
            ->first();
    }

    private function readableLabel(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 'Unknown';
        }

        return ucwords(str_replace('_', ' ', $value));
    }
}
