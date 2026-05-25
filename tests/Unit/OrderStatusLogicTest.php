<?php

use App\Models\Order;

test('delivered and paid orders are history orders', function () {
    $order = new Order([
        'status' => 'delivered',
        'payment_status' => 'paid',
    ]);

    expect($order->isHistoryOrder())->toBeTrue()
        ->and($order->isActiveOrder())->toBeFalse();
});

test('delivered orders with pending payment stay active', function () {
    $order = new Order([
        'status' => 'delivered',
        'payment_status' => 'pending',
    ]);

    expect($order->isActiveOrder())->toBeTrue()
        ->and($order->isHistoryOrder())->toBeFalse();
});

test('processing and paid orders stay active', function () {
    $order = new Order([
        'status' => 'processing',
        'payment_status' => 'paid',
    ]);

    expect($order->isActiveOrder())->toBeTrue()
        ->and($order->isHistoryOrder())->toBeFalse();
});

test('final statuses are history regardless of payment status', function (string $status) {
    $order = new Order([
        'status' => $status,
        'payment_status' => 'pending',
    ]);

    expect($order->isHistoryOrder())->toBeTrue()
        ->and($order->isActiveOrder())->toBeFalse();
})->with(['completed', 'received', 'cancelled', 'refunded', 'rejected', 'failed']);

test('only paid successful final orders count as revenue', function () {
    expect((new Order(['status' => 'delivered', 'payment_status' => 'paid']))->isRevenueOrder())->toBeTrue()
        ->and((new Order(['status' => 'completed', 'payment_status' => 'paid']))->isRevenueOrder())->toBeTrue()
        ->and((new Order(['status' => 'cancelled', 'payment_status' => 'paid']))->isRevenueOrder())->toBeFalse()
        ->and((new Order(['status' => 'refunded', 'payment_status' => 'refunded']))->isRevenueOrder())->toBeFalse()
        ->and((new Order(['status' => 'delivered', 'payment_status' => 'pending']))->isRevenueOrder())->toBeFalse();
});
