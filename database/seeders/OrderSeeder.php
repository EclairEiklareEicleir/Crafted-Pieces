<?php

namespace Database\Seeders;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\YarnColor;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $pricingService = app(PricingService::class);
        $users = User::query()->get()->keyBy('email');

        $orders = [
            [
                'public_reference' => 'ORD-DEMO-001',
                'created_at' => now()->subHours(4),
                'user_email' => 'demoniczeno@gmail.com',
                'guest_session_id' => null,
                'full_name' => 'leklek',
                'email' => 'demoniczeno@gmail.com',
                'shipping_address' => 'San Pablo, Laguna',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_001',
                'paymongo_payment_id' => 'pi_demo_001',
                'paid_at' => now()->subHours(2),
                'status' => 'delivered',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'squashed-frog-keychain', 'variant_sku' => 'SQUASHED-FROG-KEYCHAIN-GREEN-FROG-001', 'quantity' => 1],
                    ['product_slug' => 'mini-tulip-keychain', 'variant_sku' => 'MINI-TULIP-KEYCHAIN-WHITE-001', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-002',
                'created_at' => now()->subDay()->subHours(3),
                'user_email' => null,
                'guest_session_id' => 'guest-order-demo-002',
                'full_name' => 'Mia Santos',
                'email' => 'mia.santos@example.com',
                'shipping_address' => 'Cainta, Rizal',
                'payment_method' => 'PayMongo',
                'payment_status' => 'pending',
                'paymongo_checkout_id' => 'cs_demo_002',
                'paymongo_payment_id' => null,
                'paid_at' => null,
                'status' => 'awaiting_payment',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'cherry-keychain', 'quantity' => 2],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-003',
                'created_at' => now()->subDays(2),
                'user_email' => 'charlesBenito@gmail.com',
                'guest_session_id' => null,
                'full_name' => 'charles',
                'email' => 'charlesBenito@gmail.com',
                'shipping_address' => 'Quezon City, Metro Manila',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_003',
                'paymongo_payment_id' => 'pi_demo_003',
                'paid_at' => now()->subDay()->subHours(18),
                'status' => 'processing',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'mini-octopus-keychain', 'variant_sku' => 'MINI-OCTOPUS-KEYCHAIN-BLUEY-001', 'quantity' => 3],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-004',
                'created_at' => now()->subDays(9),
                'user_email' => null,
                'guest_session_id' => 'guest-order-demo-004',
                'full_name' => 'Alyssa Cruz',
                'email' => 'alyssa.cruz@example.com',
                'shipping_address' => 'Cebu City, Cebu',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_004',
                'paymongo_payment_id' => 'pi_demo_004',
                'paid_at' => now()->subDays(9)->addHours(2),
                'status' => 'received',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'mushroom-keychain', 'variant_sku' => 'MUSHROOM-KEYCHAIN-STRAWBERRY-001', 'quantity' => 1],
                    ['product_slug' => 'cherry-keychain', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-005',
                'created_at' => now()->subDays(16),
                'user_email' => 'toto@gmail.com',
                'guest_session_id' => null,
                'full_name' => 'toto',
                'email' => 'toto@gmail.com',
                'shipping_address' => 'Iloilo City, Iloilo',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_005',
                'paymongo_payment_id' => 'pi_demo_005',
                'paid_at' => now()->subDays(16)->addHours(5),
                'status' => 'shipped',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'mini-rose-bouquet-keychain', 'variant_sku' => 'MINI-ROSE-BOUQUET-KEYCHAIN-BEIGE-WRAP-001', 'quantity' => 2],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-006',
                'created_at' => now()->subMonths(2)->subDay(),
                'user_email' => null,
                'guest_session_id' => 'guest-order-demo-006',
                'full_name' => 'Nina Bautista',
                'email' => 'nina.bautista@example.com',
                'shipping_address' => 'Davao City, Davao del Sur',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_006',
                'paymongo_payment_id' => 'pi_demo_006',
                'paid_at' => now()->subMonths(2)->subHours(18),
                'status' => 'completed',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'smiley-flower-pot', 'variant_sku' => 'SMILEY-FLOWER-POT-PINK-PURPLE-001', 'quantity' => 1],
                    ['product_slug' => 'mini-tulip-keychain', 'variant_sku' => 'MINI-TULIP-KEYCHAIN-ORANGE-001', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-007',
                'created_at' => now()->subMonths(5),
                'user_email' => 'demoniczeno@gmail.com',
                'guest_session_id' => null,
                'full_name' => 'leklek',
                'email' => 'demoniczeno@gmail.com',
                'shipping_address' => 'San Pablo, Laguna',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_007',
                'paymongo_payment_id' => 'pi_demo_007',
                'paid_at' => now()->subMonths(5)->addHours(2),
                'status' => 'delivered',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'squashed-frog-keychain', 'variant_sku' => 'SQUASHED-FROG-KEYCHAIN-CYAN-FROG-001', 'quantity' => 1],
                    ['product_slug' => 'mini-rose-bouquet-keychain', 'variant_sku' => 'MINI-ROSE-BOUQUET-KEYCHAIN-BLUE-WRAP-001', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-008',
                'created_at' => now()->subMonths(8),
                'user_email' => null,
                'guest_session_id' => 'guest-order-demo-008',
                'full_name' => 'Paolo Reyes',
                'email' => 'paolo.reyes@example.com',
                'shipping_address' => 'Baguio City, Benguet',
                'payment_method' => 'PayMongo',
                'payment_status' => 'cancelled',
                'paymongo_checkout_id' => 'cs_demo_008',
                'paymongo_payment_id' => null,
                'paid_at' => null,
                'status' => 'cancelled',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'mushroom-keychain', 'variant_sku' => 'MUSHROOM-KEYCHAIN-WATERMELON-001', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-009',
                'created_at' => now()->subWeeks(2),
                'user_email' => 'charlesBenito@gmail.com',
                'guest_session_id' => null,
                'full_name' => 'charles',
                'email' => 'charlesBenito@gmail.com',
                'shipping_address' => 'Quezon City, Metro Manila',
                'payment_method' => 'PayMongo',
                'payment_status' => 'failed',
                'paymongo_checkout_id' => 'cs_demo_009',
                'paymongo_payment_id' => null,
                'paid_at' => null,
                'status' => 'failed',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'mini-octopus-keychain', 'variant_sku' => 'MINI-OCTOPUS-KEYCHAIN-PINKY-001', 'quantity' => 1],
                ],
            ],
            [
                'public_reference' => 'ORD-DEMO-010',
                'created_at' => now()->subMonths(11)->subDays(2),
                'user_email' => null,
                'guest_session_id' => 'guest-order-demo-010',
                'full_name' => 'Rhea Flores',
                'email' => 'rhea.flores@example.com',
                'shipping_address' => 'Taguig City, Metro Manila',
                'payment_method' => 'PayMongo',
                'payment_status' => 'paid',
                'paymongo_checkout_id' => 'cs_demo_010',
                'paymongo_payment_id' => 'pi_demo_010',
                'paid_at' => now()->subMonths(11)->subDay(),
                'status' => 'completed',
                'order_type' => 'online_order',
                'items' => [
                    ['product_slug' => 'tulip-pair-keychain', 'variant_sku' => 'TULIP-PAIR-KEYCHAIN-TEAL-TULIP-001', 'quantity' => 1],
                    ['product_slug' => 'cherry-keychain', 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $userId = $orderData['user_email'] ? $users->get($orderData['user_email'])?->id : null;

            $order = Order::updateOrCreate(
                ['public_reference' => $orderData['public_reference']],
                [
                    'user_id' => $userId,
                    'order_type' => $orderData['order_type'],
                    'custom_order_request_id' => null,
                    'guest_session_id' => $orderData['guest_session_id'],
                    'full_name' => $orderData['full_name'],
                    'email' => $orderData['email'],
                    'shipping_address' => $orderData['shipping_address'],
                    'payment_method' => $orderData['payment_method'],
                    'payment_status' => $orderData['payment_status'],
                    'paymongo_checkout_id' => $orderData['paymongo_checkout_id'],
                    'paymongo_payment_id' => $orderData['paymongo_payment_id'],
                    'paid_at' => $orderData['paid_at'],
                    'subtotal' => 0,
                    'platform_fee' => 0,
                    'delivery_fee' => 0,
                    'vat_amount' => 0,
                    'total_amount' => 0,
                    'status' => $orderData['status'],
                ]
            );

            $this->syncModelTimestamps($order, $orderData['created_at']);

            foreach ($orderData['items'] as $itemData) {
                $product = Product::query()->where('slug', $itemData['product_slug'])->firstOrFail();
                $variant = null;

                if (! empty($itemData['variant_sku'])) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->id)
                        ->where('sku', $itemData['variant_sku'])
                        ->firstOrFail();
                }

                $yarnColorId = null;

                if ($variant && filled($variant->yarn_color)) {
                    $yarnColor = YarnColor::query()
                        ->whereRaw('LOWER(slug) = ?', [strtolower(YarnColor::slugFor($variant->yarn_color))])
                        ->first();

                    $yarnColorId = $yarnColor?->id;
                }

                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_variant_id' => $variant?->id,
                    ],
                    [
                        'yarn_color_id' => $yarnColorId,
                        'quantity' => $itemData['quantity'],
                        'price' => (float) ($variant?->price ?? $product->price),
                        'variant_name' => $variant?->name,
                        'variant_hex_color' => $variant?->hex_color,
                        'variant_image_path' => $variant?->image_path,
                    ]
                );
            }

            $this->recalculateOrderTotals($order, $pricingService);
            $this->syncModelTimestamps($order->fresh(), $orderData['created_at']);
        }

        $customOrders = [
            [
                'email' => 'demo-custom-01@craftedpieces.test',
                'created_at' => now()->subDay()->subHours(6),
                'user_email' => 'demoniczeno@gmail.com',
                'name' => 'Dani Cruz',
                'item_type' => 'Tulip bouquet',
                'design_theme' => 'Soft pastels',
                'preferred_size' => 'Medium',
                'description' => 'Seeded inquiry for a pastel tulip bouquet with a rounded wrap.',
                'reference_image_path' => null,
                'estimated_price' => 1200,
                'final_price' => null,
                'admin_notes' => null,
                'payment_status' => 'pending',
                'payment_method' => null,
                'paymongo_checkout_id' => null,
                'paymongo_payment_id' => null,
                'status' => CustomOrderRequest::STATUS_PENDING,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_PENDING,
                'quoted_at' => null,
                'paid_at' => null,
                'payment_due_at' => null,
            ],
            [
                'email' => 'demo-custom-02@craftedpieces.test',
                'created_at' => now()->subDays(3),
                'user_email' => null,
                'name' => 'Mara Lim',
                'item_type' => 'Accessory bundle',
                'design_theme' => 'Minimal beige',
                'preferred_size' => 'Small',
                'description' => 'Requested a compact accessory bundle in beige and pink tones.',
                'reference_image_path' => null,
                'estimated_price' => 1450,
                'final_price' => null,
                'admin_notes' => 'Quoted with a cleaner wrap finish and ribbon upgrade.',
                'payment_status' => 'pending',
                'payment_method' => null,
                'paymongo_checkout_id' => null,
                'paymongo_payment_id' => null,
                'status' => CustomOrderRequest::STATUS_QUOTED,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_QUOTED,
                'quoted_at' => now()->subDays(3)->addHours(2),
                'paid_at' => null,
                'payment_due_at' => null,
            ],
            [
                'email' => 'demo-custom-03@craftedpieces.test',
                'created_at' => now()->subDays(5),
                'user_email' => 'charlesBenito@gmail.com',
                'name' => 'Rico Tan',
                'item_type' => 'Keychain set',
                'design_theme' => 'Bright mixed colors',
                'preferred_size' => 'Small',
                'description' => 'Custom keychain set with a bright mixed-color palette.',
                'reference_image_path' => null,
                'estimated_price' => 1600,
                'final_price' => 1600,
                'admin_notes' => 'Waiting for payment confirmation.',
                'payment_status' => 'pending',
                'payment_method' => 'PayMongo',
                'paymongo_checkout_id' => 'cs_demo_custom_003',
                'paymongo_payment_id' => null,
                'status' => CustomOrderRequest::STATUS_AWAITING_PAYMENT,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_ACCEPTED,
                'quoted_at' => now()->subDays(5)->addHours(1),
                'paid_at' => null,
                'payment_due_at' => now()->addDays(4),
            ],
            [
                'email' => 'demo-custom-04@craftedpieces.test',
                'created_at' => now()->subDays(8),
                'user_email' => null,
                'name' => 'Celine Dela Cruz',
                'item_type' => 'Plush frog order',
                'design_theme' => 'Green and blue',
                'preferred_size' => 'Medium',
                'description' => 'Paid custom order for a plush frog with a blue accent wrap.',
                'reference_image_path' => null,
                'estimated_price' => 1850,
                'final_price' => 1850,
                'admin_notes' => 'Payment received and production started.',
                'payment_status' => 'paid',
                'payment_method' => 'PayMongo',
                'paymongo_checkout_id' => 'cs_demo_custom_004',
                'paymongo_payment_id' => 'pi_demo_custom_004',
                'status' => CustomOrderRequest::STATUS_PAID,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_ACCEPTED,
                'quoted_at' => now()->subDays(8)->addHours(1),
                'paid_at' => now()->subDays(7)->addHours(4),
                'payment_due_at' => now()->addDays(1),
            ],
            [
                'email' => 'demo-custom-05@craftedpieces.test',
                'created_at' => now()->subWeeks(3),
                'user_email' => 'toto@gmail.com',
                'name' => 'Noah Garcia',
                'item_type' => 'Bouquet piece',
                'design_theme' => 'Sunset gradient',
                'preferred_size' => 'Large',
                'description' => 'In-progress bouquet commission with a sunset gradient palette.',
                'reference_image_path' => null,
                'estimated_price' => 2100,
                'final_price' => 2100,
                'admin_notes' => 'Currently in progress with final assembly pending.',
                'payment_status' => 'paid',
                'payment_method' => 'PayMongo',
                'paymongo_checkout_id' => 'cs_demo_custom_005',
                'paymongo_payment_id' => 'pi_demo_custom_005',
                'status' => CustomOrderRequest::STATUS_IN_PROGRESS,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_ACCEPTED,
                'quoted_at' => now()->subWeeks(3)->addHours(1),
                'paid_at' => now()->subWeeks(3)->addHours(4),
                'payment_due_at' => now()->addDays(2),
            ],
            [
                'email' => 'demo-custom-06@craftedpieces.test',
                'created_at' => now()->subMonths(2),
                'user_email' => null,
                'name' => 'Isabel Forte',
                'item_type' => 'Completed commission',
                'design_theme' => 'Blue and beige',
                'preferred_size' => 'Medium',
                'description' => 'Completed demo commission with a blue and beige palette.',
                'reference_image_path' => null,
                'estimated_price' => 2400,
                'final_price' => 2400,
                'admin_notes' => 'Delivered and marked complete for dashboard history.',
                'payment_status' => 'paid',
                'payment_method' => 'PayMongo',
                'paymongo_checkout_id' => 'cs_demo_custom_006',
                'paymongo_payment_id' => 'pi_demo_custom_006',
                'status' => CustomOrderRequest::STATUS_COMPLETED,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_ACCEPTED,
                'quoted_at' => now()->subMonths(2)->addDays(1),
                'paid_at' => now()->subMonths(2)->addDays(2),
                'payment_due_at' => now()->subMonths(2)->addDays(7),
            ],
            [
                'email' => 'demo-custom-07@craftedpieces.test',
                'created_at' => now()->subMonths(4),
                'user_email' => null,
                'name' => 'Elaine Rivera',
                'item_type' => 'Seasonal gift',
                'design_theme' => 'Muted autumn',
                'preferred_size' => 'Small',
                'description' => 'Rejected demo request to keep the dashboard status mix realistic.',
                'reference_image_path' => null,
                'estimated_price' => 900,
                'final_price' => null,
                'admin_notes' => 'Rejected because the requested timeline was not feasible.',
                'payment_status' => 'cancelled',
                'payment_method' => null,
                'paymongo_checkout_id' => null,
                'paymongo_payment_id' => null,
                'status' => CustomOrderRequest::STATUS_REJECTED,
                'quote_status' => CustomOrderRequest::QUOTE_STATUS_DECLINED,
                'quoted_at' => now()->subMonths(4)->addHours(3),
                'paid_at' => null,
                'payment_due_at' => null,
            ],
        ];

        foreach ($customOrders as $customOrderData) {
            $userId = $customOrderData['user_email'] ? $users->get($customOrderData['user_email'])?->id : null;
            $createdAt = $customOrderData['created_at'];

            $customOrder = CustomOrderRequest::updateOrCreate(
                ['email' => $customOrderData['email']],
                [
                    'user_id' => $userId,
                    'name' => $customOrderData['name'],
                    'email' => $customOrderData['email'],
                    'item_type' => $customOrderData['item_type'],
                    'design_theme' => $customOrderData['design_theme'],
                    'preferred_size' => $customOrderData['preferred_size'],
                    'description' => $customOrderData['description'],
                    'reference_image_path' => $customOrderData['reference_image_path'],
                    'estimated_price' => $customOrderData['estimated_price'],
                    'final_price' => $customOrderData['final_price'],
                    'admin_notes' => $customOrderData['admin_notes'],
                    'payment_status' => $customOrderData['payment_status'],
                    'payment_method' => $customOrderData['payment_method'],
                    'paymongo_checkout_id' => $customOrderData['paymongo_checkout_id'],
                    'paymongo_payment_id' => $customOrderData['paymongo_payment_id'],
                    'status' => $customOrderData['status'],
                    'quote_status' => $customOrderData['quote_status'],
                    'quoted_at' => $customOrderData['quoted_at'],
                    'paid_at' => $customOrderData['paid_at'],
                    'payment_due_at' => $customOrderData['payment_due_at'],
                ]
            );

            $this->syncModelTimestamps($customOrder, $createdAt);

            $linkedOrder = $customOrder->syncLinkedOrder();

            if ($linkedOrder) {
                $this->syncModelTimestamps($linkedOrder, $createdAt);
            }
        }
    }

    private function recalculateOrderTotals(Order $order, PricingService $pricingService): void
    {
        $order->loadMissing('items');

        $totals = $pricingService->calculateFromOrder($order);

        $order->forceFill([
            'subtotal' => $totals['subtotal'],
            'platform_fee' => $totals['platform_fee'],
            'delivery_fee' => $totals['delivery_fee'],
            'vat_amount' => $totals['vat'],
            'total_amount' => $totals['total'],
        ]);

        $order->timestamps = false;
        $order->saveQuietly();
        $order->timestamps = true;
    }

    private function syncModelTimestamps($model, Carbon $timestamp): void
    {
        $model->forceFill([
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        $model->timestamps = false;
        $model->saveQuietly();
        $model->timestamps = true;
    }
}