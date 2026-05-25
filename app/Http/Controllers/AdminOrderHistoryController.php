<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminOrderHistoryController extends Controller
{
    private const ORDER_HISTORY_STATUSES = Order::HISTORY_ORDER_STATUSES;

    private const CUSTOM_HISTORY_STATUSES = [
        CustomOrderRequest::STATUS_COMPLETED,
        CustomOrderRequest::STATUS_REJECTED,
    ];

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

    public function export(Request $request): Response
    {
        $search = trim((string) $request->input('search', ''));
        $status = trim((string) $request->input('status', ''));
        $paymentStatus = trim((string) $request->input('payment_status', ''));
        $type = trim((string) $request->input('type', ''));
        $from = trim((string) $request->input('from', ''));
        $to = trim((string) $request->input('to', ''));

        $records = $this->historyRecords($search, $status, $paymentStatus, $type, $from, $to);
        $totalSales = $records->sum(fn (array $entry) => $entry['counts_as_sale'] ? (float) $entry['total_amount'] : 0);

        $rows = $records->map(function (array $entry): array {
            return [
                (int) $entry['record_id'],
                $entry['reference'] ?? ('ORD-' . $entry['record_id']),
                $entry['customer_name'],
                $entry['customer_email'],
                $entry['type_label'],
                $entry['status'],
                $entry['payment_status'],
                (float) $entry['total_amount'],
                $entry['ordered_at']?->format('Y-m-d H:i:s'),
                $entry['updated_at']?->format('Y-m-d H:i:s'),
            ];
        })->all();

        $columns = [
            'Order ID',
            'Reference',
            'Customer Name',
            'Email',
            'Order Type',
            'Order Status',
            'Payment Status',
            'Total Amount',
            'Date Ordered',
            'Last Updated',
        ];

        $metadata = [
            'Generated Date' => now()->format('Y-m-d H:i:s'),
            'Applied Filters' => $this->exportFilterSummary($search, $status, $paymentStatus, $type, $from, $to),
            'Total Records Exported' => (string) count($rows),
            'Total Paid Sales' => 'PHP ' . number_format((float) $totalSales, 2),
        ];

        $html = $this->buildExcelHtml(
            'Crafted Pieces Order History Report',
            $metadata,
            $columns,
            $rows,
            $totalSales
        );

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="crafted_pieces_order_history_report_' . now()->format('Y-m-d') . '.xls"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function destroy(string $recordType, int $recordId)
    {
        $record = $this->findDeletableHistoryRecord($recordType, $recordId);

        if (! $record) {
            return back()->with('error', 'Only order history records can be deleted.');
        }

        $record->delete();

        return back()->with('success', 'Order history record deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $recordKeys = $request->input('records', []);

        if (! is_array($recordKeys) || count(array_filter($recordKeys)) === 0) {
            return back()->with('error', 'Please select at least one order history record.');
        }

        $records = collect();

        foreach (array_unique(array_filter($recordKeys)) as $recordKey) {
            if (! is_string($recordKey) || ! preg_match('/^(order|custom):([0-9]+)$/', $recordKey, $matches)) {
                return back()->with('error', 'Only order history records can be deleted.');
            }

            $record = $this->findDeletableHistoryRecord($matches[1], (int) $matches[2]);

            if (! $record) {
                return back()->with('error', 'Only order history records can be deleted.');
            }

            $records->push($record);
        }

        if ($records->isEmpty()) {
            return back()->with('error', 'Please select at least one order history record.');
        }

        DB::transaction(function () use ($records): void {
            $records->each->delete();
        });

        return back()->with('success', 'Selected order history records deleted successfully.');
    }

    private function historyRecords(string $search, string $status, string $paymentStatus, string $type, string $from, string $to): Collection
    {
        $records = collect();

        $records = $records->merge(
            $this->filterOrders($search, $status, $paymentStatus, $type, $from, $to)
                ->get()
                ->map(fn (Order $order) => $this->mapOrderRecord($order))
        );

        if ($type !== 'product') {
            $records = $records->merge(
                $this->filterCustomOrders($search, $status, $paymentStatus, $type, $from, $to)
                    ->get()
                    ->map(fn (CustomOrderRequest $order) => $this->mapCustomOrderRecord($order))
            );
        }

        return $records->sortByDesc('sort_key')->values();
    }

    private function filterOrders(string $search, string $status, string $paymentStatus, string $type, string $from, string $to)
    {
        $query = Order::query()
            ->with('user')
            ->historyOrders();

        if ($type === 'product') {
            $query->whereIn('order_type', ['online_order', 'walk_in_order']);
        } elseif ($type === 'custom') {
            $query->where('order_type', 'custom_order');
        }

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

    private function filterCustomOrders(string $search, string $status, string $paymentStatus, string $type, string $from, string $to)
    {
        $query = CustomOrderRequest::query()
            ->with('user')
            ->whereIn('status', self::CUSTOM_HISTORY_STATUSES)
            ->whereDoesntHave('order', fn ($query) => $query->withTrashed());

        if ($type === 'product') {
            return $query->whereRaw('1 = 0');
        }

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
        $isCustomOrder = ($order->order_type ?? 'online_order') === 'custom_order';

        return [
            'record_key' => 'order:' . $order->id,
            'record_type' => 'order',
            'type' => $isCustomOrder ? 'custom' : 'product',
            'type_label' => $order->order_type_label,
            'order_type' => $order->order_type ?? 'online_order',
            'record_id' => $order->id,
            'reference' => $order->public_reference,
            'customer_name' => $order->full_name ?: ($order->user?->name ?? 'Unknown customer'),
            'customer_email' => $order->email ?: ($order->user?->email ?? '—'),
            'status' => $order->status ?? 'delivered',
            'payment_status' => $order->payment_status ?? 'unpaid',
            'total_amount' => (float) ($order->total_amount ?? 0),
            'ordered_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'details_url' => route('admin.orders.show', $order),
            'payment_url' => route('admin.orders.receipt', $order),
            'delete_url' => route('admin.history.destroy', ['recordType' => 'order', 'recordId' => $order->id]),
            'counts_as_sale' => $order->isRevenueOrder(),
            'sort_key' => $order->updated_at?->getTimestamp() ?? $order->created_at?->getTimestamp() ?? 0,
        ];
    }

    private function mapCustomOrderRecord(CustomOrderRequest $order): array
    {
        $paymentStatus = $order->payment_status ?? 'unpaid';
        $status = $order->status ?? CustomOrderRequest::STATUS_COMPLETED;

        return [
            'record_key' => 'custom:' . $order->id,
            'record_type' => 'custom',
            'type' => 'custom',
            'type_label' => 'Custom Order',
            'order_type' => 'custom_order',
            'record_id' => $order->id,
            'reference' => null,
            'customer_name' => $order->name ?: ($order->user?->name ?? 'Unknown customer'),
            'customer_email' => $order->email ?: ($order->user?->email ?? '—'),
            'status' => $status,
            'payment_status' => $paymentStatus,
            'total_amount' => (float) ($order->final_price ?? $order->estimated_price ?? 0),
            'ordered_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'details_url' => route('admin.custom.show', $order),
            'payment_url' => route('admin.custom.receipt', $order),
            'delete_url' => route('admin.history.destroy', ['recordType' => 'custom', 'recordId' => $order->id]),
            'counts_as_sale' => $status === CustomOrderRequest::STATUS_COMPLETED
                && Order::normalizeStatus($paymentStatus) === 'paid',
            'sort_key' => $order->updated_at?->getTimestamp() ?? $order->created_at?->getTimestamp() ?? 0,
        ];
    }

    private function findDeletableHistoryRecord(string $recordType, int $recordId): ?Model
    {
        if ($recordType === 'order') {
            $order = Order::find($recordId);

            return $order?->isHistoryOrder() ? $order : null;
        }

        if ($recordType === 'custom') {
            $customOrder = CustomOrderRequest::whereDoesntHave('order', fn ($query) => $query->withTrashed())
                ->find($recordId);

            if (! $customOrder) {
                return null;
            }

            return in_array(Order::normalizeStatus($customOrder->status), self::CUSTOM_HISTORY_STATUSES, true)
                ? $customOrder
                : null;
        }

        return null;
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

    private function exportFilterSummary(string $search, string $status, string $paymentStatus, string $type, string $from, string $to): string
    {
        $filters = [];

        if ($search !== '') {
            $filters[] = 'Search: ' . $search;
        }

        if ($type !== '') {
            $filters[] = 'Type: ' . $type;
        }

        if ($status !== '') {
            $filters[] = 'Status: ' . $status;
        }

        if ($paymentStatus !== '') {
            $filters[] = 'Payment: ' . $paymentStatus;
        }

        if ($from !== '') {
            $filters[] = 'From: ' . $from;
        }

        if ($to !== '') {
            $filters[] = 'To: ' . $to;
        }

        return $filters === [] ? 'None' : implode(' | ', $filters);
    }

    private function buildExcelHtml(string $title, array $metadata, array $columns, array $rows, float $totalSales): string
    {
        $html = '<html><head><meta charset="UTF-8"><style>'
            . 'body{font-family:Calibri,Arial,sans-serif;background:#ffffff;color:#3f1d28;}'
            . '.title{font-size:20px;font-weight:700;color:#650c2a;padding:14px 10px;border-bottom:2px solid #f3c9d9;}'
            . '.meta{background:#fff6fa;color:#3f1d28;border:1px solid #f3c9d9;padding:6px 10px;}'
            . 'table{border-collapse:collapse;width:100%;margin-top:12px;}'
            . 'th{background:#650c2a;color:#ffffff;font-weight:700;text-align:center;border:1px solid #f3c9d9;padding:8px;}'
            . 'td{border:1px solid #f3c9d9;padding:7px;color:#3f1d28;vertical-align:top;}'
            . 'tr.alt td{background:#fff6fa;}'
            . '.num{text-align:right;}'
            . '.status-ok{background:#e9f8ef;color:#176235;font-weight:600;}'
            . '.status-mid{background:#fff3dc;color:#7b4b0e;font-weight:600;}'
            . '.status-bad{background:#fde8ea;color:#8a1f2f;font-weight:600;}'
            . '.status-refund{background:#f0edf9;color:#49386e;font-weight:600;}'
            . '.total-row td{background:#f9ecf2;font-weight:700;color:#650c2a;}'
            . '</style></head><body>';

        $html .= '<table><tr><td class="title" colspan="' . count($columns) . '">' . $this->escapeExcel($title) . '</td></tr>';

        foreach ($metadata as $label => $value) {
            $html .= '<tr><td class="meta" colspan="2"><strong>' . $this->escapeExcel((string) $label) . ':</strong> ' . $this->escapeExcel((string) $value) . '</td>'
                . '<td class="meta" colspan="' . max(1, count($columns) - 2) . '"></td></tr>';
        }

        $html .= '<tr>';
        foreach ($columns as $column) {
            $html .= '<th>' . $this->escapeExcel($column) . '</th>';
        }
        $html .= '</tr>';

        foreach ($rows as $index => $row) {
            $html .= '<tr' . ($index % 2 === 1 ? ' class="alt"' : '') . '>';

            foreach ($row as $columnIndex => $value) {
                $classes = [];

                if ($columnIndex === 7) {
                    $classes[] = 'num';
                    $value = 'PHP ' . number_format((float) $value, 2);
                }

                if ($columnIndex === 5 || $columnIndex === 6) {
                    $classes[] = $this->statusClass((string) $value);
                }

                $classAttr = $classes !== [] ? ' class="' . implode(' ', array_filter($classes)) . '"' : '';
                $html .= '<td' . $classAttr . '>' . $this->escapeExcel((string) $value) . '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '<tr class="total-row">';
        $html .= '<td colspan="7">Total Sales</td>';
        $html .= '<td class="num">' . $this->escapeExcel('PHP ' . number_format($totalSales, 2)) . '</td>';
        $html .= '<td colspan="2"></td>';
        $html .= '</tr>';

        $html .= '</table></body></html>';

        return $html;
    }

    private function statusClass(string $status): string
    {
        $normalized = Order::normalizeStatus($status);

        if (in_array($normalized, ['paid', 'completed', 'received', 'active', 'delivered'], true)) {
            return 'status-ok';
        }

        if (in_array($normalized, ['pending', 'processing', 'awaiting_payment'], true)) {
            return 'status-mid';
        }

        if (in_array($normalized, ['refunded'], true)) {
            return 'status-refund';
        }

        if (in_array($normalized, ['cancelled', 'rejected', 'failed', 'expired', 'out_of_stock', 'unpaid'], true)) {
            return 'status-bad';
        }

        return '';
    }

    private function escapeExcel(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
