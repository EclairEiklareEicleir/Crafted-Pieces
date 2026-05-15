<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\CustomOrderRequest;
use Carbon\Carbon;

class AnalyticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================
        // LAST MONTH BASE DATE
        // =========================
        $lastMonth = Carbon::now()->subMonth();

        // =========================
        // FAKE ORDERS (LAST MONTH)
        // =========================
        for ($i = 1; $i <= 10; $i++) {

            Order::create([
                'user_id' => null,
                'full_name' => "Test Customer $i",
                'email' => "test$i@example.com",
                'shipping_address' => "Test Address $i",
                'payment_method' => 'GCash',
                'total_amount' => rand(500, 3000),
                'status' => 'completed',
                'created_at' => $lastMonth->copy()->addDays(rand(0, 27)),
                'updated_at' => $lastMonth->copy()->addDays(rand(0, 27)),
            ]);
        }

        // =========================
        // FAKE CUSTOM REQUESTS (LAST MONTH)
        // =========================
        for ($i = 1; $i <= 6; $i++) {

            CustomOrderRequest::create([
                'user_id' => null,
                'name' => "Client $i",
                'email' => "client$i@example.com",
                'item_type' => 'Crochet Item',
                'design_theme' => 'Aesthetic',
                'preferred_size' => '20cm',
                'description' => 'Test commission request',
                'estimated_price' => rand(800, 2500),
                'status' => 'completed',
                'created_at' => $lastMonth->copy()->addDays(rand(0, 27)),
                'updated_at' => $lastMonth->copy()->addDays(rand(0, 27)),
            ]);
        }
    }
}