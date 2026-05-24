@extends('layouts.admin')

@section('content')

@php
    $filters = [
        'today' => 'Today',
        'week' => 'This Week',
        'month' => 'This Month',
        'year' => 'This Year',
        'all' => 'All Time',
    ];

    $money = fn ($amount) => 'PHP ' . number_format((float) $amount, 2);
@endphp

<div class="space-y-8">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Store Overview
            </p>
            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Admin Dashboard
            </h1>
            <p class="mt-2 text-sm text-brand-ink/65">
                Sales, order health, stock alerts, and recent activity from real system records.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach ($filters as $key => $label)
                <a href="{{ route('admin.dashboard', ['filter' => $key]) }}"
                   class="rounded-full px-5 py-2 text-sm font-semibold transition
                   {{ $filter === $key
                        ? 'bg-brand-primary text-white shadow-sm'
                        : 'border border-brand-border bg-white text-brand-primary hover:bg-brand-light/40' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-brand-ink/60">Revenue</p>
            <p class="mt-4 text-3xl font-bold text-brand-primary">{{ $money($totalRevenue) }}</p>
            <p class="mt-2 text-xs text-brand-ink/55">Paid orders with delivered, received, or completed status.</p>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-brand-ink/60">Orders</p>
            <p class="mt-4 text-3xl font-bold text-brand-primary">{{ $totalOrders }}</p>
            <p class="mt-2 text-xs text-brand-ink/55">{{ $pendingOrders }} pending, {{ $completedOrders }} completed.</p>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-brand-ink/60">Paid Orders</p>
            <p class="mt-4 text-3xl font-bold text-brand-primary">{{ $paidOrders }}</p>
            <p class="mt-2 text-xs text-brand-ink/55">{{ $cancelledOrders }} cancelled, refunded, rejected, or failed.</p>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-brand-ink/60">Custom Orders</p>
            <p class="mt-4 text-3xl font-bold text-brand-primary">{{ $totalCommissions }}</p>
            <p class="mt-2 text-xs text-brand-ink/55">{{ $activeCommissions }} active, {{ $resolvedCommissions }} resolved.</p>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="font-display text-2xl font-semibold text-brand-primary">Sales Overview</h2>
                    <p class="mt-1 text-sm text-brand-ink/60">Revenue from paid, final orders only.</p>
                </div>
                <span class="brand-pill">{{ $filters[$filter] }}</span>
            </div>
            <div class="mt-6 h-72">
                <canvas id="salesOverviewChart" class="h-full w-full"></canvas>
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Stock Watch</h2>
            <div class="mt-5 grid gap-3">
                <div class="flex items-center justify-between rounded-2xl bg-brand-light/35 p-4">
                    <span class="text-sm text-brand-ink/70">Products</span>
                    <span class="font-semibold text-brand-primary">{{ $totalProducts }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-amber-50 p-4">
                    <span class="text-sm text-amber-900">Low stock products</span>
                    <span class="font-semibold text-amber-900">{{ $lowStockProducts }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-rose-50 p-4">
                    <span class="text-sm text-rose-800">Out of stock products</span>
                    <span class="font-semibold text-rose-800">{{ $outOfStockProducts }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-brand-light/35 p-4">
                    <span class="text-sm text-brand-ink/70">Low / out variants</span>
                    <span class="font-semibold text-brand-primary">{{ $lowStockVariants }} / {{ $outOfStockVariants }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Order Status</h2>
            <div class="mt-6 h-64">
                <canvas id="orderStatusChart" class="h-full w-full"></canvas>
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Payment Status</h2>
            <div class="mt-6 h-64">
                <canvas id="paymentStatusChart" class="h-full w-full"></canvas>
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Order Types</h2>
            <div class="mt-6 h-64">
                <canvas id="orderTypeChart" class="h-full w-full"></canvas>
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Best-Selling Products</h2>
            <div class="mt-6 h-72">
                <canvas id="bestProductsChart" class="h-full w-full"></canvas>
            </div>

            <div class="mt-5 space-y-2">
                @forelse ($bestSellingProducts as $product)
                    <div class="flex items-center justify-between rounded-2xl bg-brand-light/30 px-4 py-3 text-sm">
                        <span class="font-semibold text-brand-primary">{{ $product['name'] }}</span>
                        <span class="text-brand-ink/65">{{ $product['quantity'] }} sold</span>
                    </div>
                @empty
                    <p class="text-sm text-brand-ink/60">No paid final product sales yet.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Best-Selling Variants</h2>
            <div class="mt-6 h-72">
                <canvas id="bestVariantsChart" class="h-full w-full"></canvas>
            </div>

            <div class="mt-5 space-y-2">
                @forelse ($bestSellingVariants as $variant)
                    <div class="flex items-center justify-between rounded-2xl bg-brand-light/30 px-4 py-3 text-sm">
                        <span class="font-semibold text-brand-primary">{{ $variant['name'] }}</span>
                        <span class="text-brand-ink/65">{{ $variant['quantity'] }} sold</span>
                    </div>
                @empty
                    <p class="text-sm text-brand-ink/60">No variant sales found yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Quick Sales</h2>
            <div class="mt-5 grid gap-3">
                <div class="rounded-2xl bg-brand-light/35 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Today</p>
                    <p class="mt-2 text-xl font-bold text-brand-primary">{{ $money($salesToday) }}</p>
                </div>
                <div class="rounded-2xl bg-brand-light/35 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">This Month</p>
                    <p class="mt-2 text-xl font-bold text-brand-primary">{{ $money($salesThisMonth) }}</p>
                </div>
                <div class="rounded-2xl bg-brand-light/35 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Top Customer</p>
                    <p class="mt-2 text-base font-bold text-brand-primary">{{ $topCustomer['name'] ?? 'N/A' }}</p>
                    @if ($topCustomer)
                        <p class="mt-1 text-xs text-brand-ink/60">{{ $topCustomer['orders'] }} orders, {{ $money($topCustomer['total']) }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-display text-2xl font-semibold text-brand-primary">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-brand-secondary hover:text-brand-primary">View Active Orders</a>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[44rem] text-left text-sm">
                    <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">
                        <tr>
                            <th class="py-3 pr-4">Order</th>
                            <th class="py-3 pr-4">Customer</th>
                            <th class="py-3 pr-4">Type</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Payment</th>
                            <th class="py-3 pr-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-3 pr-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-brand-primary hover:text-brand-secondary">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="py-3 pr-4 text-brand-ink/70">{{ $order->full_name ?: ($order->user?->name ?? 'Unknown') }}</td>
                                <td class="py-3 pr-4"><span class="brand-pill">{{ $order->order_type_label }}</span></td>
                                <td class="py-3 pr-4"><x-status-badge :status="$order->status" context="order" /></td>
                                <td class="py-3 pr-4"><x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" /></td>
                                <td class="py-3 pr-4 text-right font-semibold text-brand-primary">{{ $money($order->total_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-brand-ink/60">No recent orders.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Recent Custom Requests</h2>
            <a href="{{ route('admin.custom.index') }}" class="text-sm font-semibold text-brand-secondary hover:text-brand-primary">View Custom Orders</a>
        </div>

        <div class="mt-5 grid gap-3 lg:grid-cols-2">
            @forelse ($recentRequests as $request)
                <a href="{{ route('admin.custom.show', $request) }}" class="rounded-3xl border border-brand-border bg-brand-light/25 p-4 transition hover:-translate-y-0.5 hover:bg-brand-light/40 hover:shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-brand-primary">#{{ $request->id }} {{ $request->item_type }}</p>
                            <p class="mt-1 text-sm text-brand-ink/60">{{ $request->name }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <x-status-badge :status="$request->status" context="custom" />
                            <x-status-badge :status="$request->payment_status ?? 'unpaid'" context="payment" />
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-sm text-brand-ink/60">No custom requests yet.</p>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chartData = @json($chartData);
    const palette = ['#650c2a', '#cf4f7a', '#f79eb8', '#8d5a44', '#2f7f73', '#805ad5', '#d97706', '#475569'];
    const gridColor = 'rgba(101, 12, 42, 0.08)';
    const textColor = '#3f1d28';

    if (!window.Chart) {
        return;
    }

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";

    const hasData = (values) => Array.isArray(values) && values.some((value) => Number(value) > 0);

    const emptyPlugin = {
        id: 'emptyState',
        afterDraw(chart) {
            const values = chart.data.datasets?.[0]?.data || [];
            if (hasData(values)) {
                return;
            }

            const { ctx, chartArea } = chart;
            if (!chartArea) {
                return;
            }

            ctx.save();
            ctx.fillStyle = 'rgba(63, 29, 40, 0.55)';
            ctx.textAlign = 'center';
            ctx.font = '600 13px sans-serif';
            ctx.fillText('No matching data yet', (chartArea.left + chartArea.right) / 2, (chartArea.top + chartArea.bottom) / 2);
            ctx.restore();
        },
    };

    const buildChart = (id, config) => {
        const canvas = document.getElementById(id);
        if (!canvas) {
            return;
        }

        new Chart(canvas, config);
    };

    buildChart('salesOverviewChart', {
        type: 'line',
        data: {
            labels: chartData.salesOverview.labels,
            datasets: [{
                label: 'Revenue',
                data: chartData.salesOverview.values,
                borderColor: '#650c2a',
                backgroundColor: 'rgba(207, 79, 122, 0.16)',
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointBackgroundColor: '#650c2a',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `Revenue: PHP ${Number(context.parsed.y || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                    },
                },
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: gridColor } },
            },
        },
        plugins: [emptyPlugin],
    });

    const doughnut = (id, source) => buildChart(id, {
        type: 'doughnut',
        data: {
            labels: source.labels,
            datasets: [{
                data: source.values,
                backgroundColor: palette,
                borderColor: '#fff',
                borderWidth: 3,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } },
            },
        },
        plugins: [emptyPlugin],
    });

    doughnut('orderStatusChart', chartData.orderStatuses);
    doughnut('paymentStatusChart', chartData.paymentStatuses);
    doughnut('orderTypeChart', chartData.orderTypes);

    const bar = (id, source, label) => buildChart(id, {
        type: 'bar',
        data: {
            labels: source.labels,
            datasets: [{
                label,
                data: source.values,
                backgroundColor: '#cf4f7a',
                borderRadius: 12,
                maxBarThickness: 40,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: gridColor } },
                y: { grid: { display: false } },
            },
        },
        plugins: [emptyPlugin],
    });

    bar('bestProductsChart', chartData.bestSellingProducts, 'Sold');
    bar('bestVariantsChart', chartData.bestSellingVariants, 'Sold');
});
</script>

@endsection
