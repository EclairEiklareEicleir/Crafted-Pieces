<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AdminOrderHistoryController extends Controller
{
    private const PRODUCT_HISTORY_STATUSES = ['delivered', 'received', 'cancelled'];

    private const CUSTOM_HISTORY_STATUSES = ['completed', 'rejected'];

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = trim((string) $request->input('status', ''));
        $paymentStatus = trim((string) $request->input('payment_status', ''));
        $type = trim((string) $request->input('type', ''));
        $from = trim((string) $request->input('from', ''));
        $to = trim((string) $request->input('to', ''));

        $records = $this->historyRecords($search, $status, $paymentStatus, $type, $from, $to);
        $history = $this->paginateRecords($records, 12, $request);

        return view('admin.history.index', compact('history'));
    }

    private function historyRecords(string $search, string $status, string $paymentStatus, string $type, string $from, string $to): Collection
    {
        $records = collect();

        if ($type !== 'custom') {
            $records = $records->merge(
                $this->filterProductOrders($search, $status, $paymentStatus, $from, $to)
                    ->get()
                    ->map(fn (Order $order) => $this->mapOrderRecord($order))
            );
        }

        if ($type !== 'product') {
            $records = $records->merge(
                $this->filterCustomOrders($search, $status, $paymentStatus, $from, $to)
                    ->get()
                    ->map(fn (CustomOrderRequest $order) => $this->mapCustomOrderRecord($order))
            );
        }

        return $records->sortByDesc('sort_key')->values();
    }

    private function filterProductOrders(string $search, string $status, string $paymentStatus, string $from, string $to)
    {
        $query = Order::query()
            ->with('user')
            ->whereIn('status', self::PRODUCT_HISTORY_STATUSES);

        $this->applySearchFilter($query, $search, 'full_name', 'email');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($paymentStatus !== '') {
            $query->where('payment_status', $paymentStatus);
        }

        $this->applyDateFilters($query, $from, $to);

        return $query->latest();
    }

    private function filterCustomOrders(string $search, string $status, string $paymentStatus, string $from, string $to)
    {
        $query = CustomOrderRequest::query()
            ->with('user')
            ->whereIn('status', self::CUSTOM_HISTORY_STATUSES);

        $this->applySearchFilter($query, $search, 'name', 'email');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($paymentStatus !== '') {
            $query->where('payment_status', $paymentStatus);
        }

        $this->applyDateFilters($query, $from, $to);

        return $query->latest();
    }

    private function applySearchFilter($query, string $search, string $nameColumn, string $emailColumn): void
    {
        if ($search === '') {
            return;
        }

        $query->where(function ($builder) use ($search, $nameColumn, $emailColumn) {
            $builder->where($nameColumn, 'like', '%' . $search . '%')
                ->orWhere($emailColumn, 'like', '%' . $search . '%');

            if (ctype_digit($search)) {
                $builder->orWhere('id', (int) $search);
            }
        });
    }

    private function applyDateFilters($query, string $from, string $to): void
    {
        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }
    }

    private function mapOrderRecord(Order $order): array
    {
        return [
            'type' => 'product',
            'type_label' => 'Product Order',
            'record_id' => $order->id,
            'customer_name' => $order->full_name ?: ($order->user?->name ?? 'Unknown customer'),
            'customer_email' => $order->email ?: ($order->user?->email ?? '—'),
            'status' => $order->status ?? 'delivered',
            'payment_status' => $order->payment_status ?? 'unpaid',
            'total_amount' => (float) ($order->total_amount ?? 0),
            'ordered_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'details_url' => route('admin.orders.show', $order),
            'payment_url' => route('admin.orders.receipt', $order),
            'sort_key' => $order->updated_at?->getTimestamp() ?? $order->created_at?->getTimestamp() ?? 0,
        ];
    }

    private function mapCustomOrderRecord(CustomOrderRequest $order): array
    {
        return [
            'type' => 'custom',
            'type_label' => 'Custom Order',
            'record_id' => $order->id,
            'customer_name' => $order->name ?: ($order->user?->name ?? 'Unknown customer'),
            'customer_email' => $order->email ?: ($order->user?->email ?? '—'),
            'status' => $order->status ?? 'completed',
            'payment_status' => $order->payment_status ?? 'unpaid',
            'total_amount' => (float) ($order->final_price ?? $order->estimated_price ?? 0),
            'ordered_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'details_url' => route('admin.custom.show', $order),
            'payment_url' => route('admin.custom.receipt', $order),
            'sort_key' => $order->updated_at?->getTimestamp() ?? $order->created_at?->getTimestamp() ?? 0,
        ];
    }

    private function paginateRecords(Collection $records, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage();

        $items = $records->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($items, $records->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}