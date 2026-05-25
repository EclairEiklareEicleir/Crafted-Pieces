<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('admin_notes');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('paymongo_checkout_id')->nullable()->after('payment_method');
            $table->string('paymongo_payment_id')->nullable()->after('paymongo_checkout_id');
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'paymongo_checkout_id',
                'paymongo_payment_id',
            ]);
        });
    }
};