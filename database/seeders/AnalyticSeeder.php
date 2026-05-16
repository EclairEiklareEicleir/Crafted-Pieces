<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use Carbon\Carbon;

class AnalyticSeeder extends Seeder
{
    public function run(): void
    {
        $base = Carbon::now()->subMonths(2);

        // =========================
        // ORDERS (REALISTIC FLOW)
        // =========================
        $statuses = [
            'pending',
            'shipped',
            'delivered',
            'received',
            'cancelled'
        ];

        for ($i = 1; $i <= 20; $i++) {

            Order::create([
                'user_id' => null,
                'full_name' => "Seed Customer $i",
                'email' => "seed$i@example.com",
                'shipping_address' => "Sample Address $i",
                'payment_method' => 'GCash',
                'total_amount' => rand(300, 3500),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $base->copy()->addDays(rand(0, 60)),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // CUSTOM ORDERS (REAL FLOW)
        // =========================
        $customStatuses = [
            'pending',
            'in_discussion',
            'awaiting_payment',
            'paid',
            'rejected',
            'completed'
        ];

        for ($i = 1; $i <= 10; $i++) {

            CustomOrderRequest::create([
                'user_id' => null,
                'name' => "Client Seed $i",
                'email' => "clientseed$i@example.com",
                'item_type' => 'Crochet Item',
                'design_theme' => 'Aesthetic',
                'preferred_size' => '20cm',
                'description' => 'Seeded commission request',
                'estimated_price' => rand(500, 3000),
                'status' => $customStatuses[array_rand($customStatuses)],
                'created_at' => $base->copy()->addDays(rand(0, 60)),
                'updated_at' => now(),
            ]);
        }
    }
}