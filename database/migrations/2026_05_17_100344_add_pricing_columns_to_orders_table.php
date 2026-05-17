<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal('subtotal', 10, 2)
                ->default(0)
                ->after('payment_method');

            $table->decimal('platform_fee', 10, 2)
                ->default(0)
                ->after('subtotal');

            $table->decimal('delivery_fee', 10, 2)
                ->default(0)
                ->after('platform_fee');

            $table->decimal('vat_amount', 10, 2)
                ->default(0)
                ->after('delivery_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'subtotal',
                'platform_fee',
                'delivery_fee',
                'vat_amount',
            ]);
        });
    }
};