<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->string('paymongo_checkout_id')->nullable()->after('payment_status');
            $table->string('paymongo_payment_id')->nullable()->after('paymongo_checkout_id');
            $table->timestamp('paid_at')->nullable()->after('paymongo_payment_id');
        });

        DB::table('orders')->update([
            'payment_status' => 'unpaid',
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'paymongo_checkout_id',
                'paymongo_payment_id',
                'paid_at',
            ]);
        });
    }
};