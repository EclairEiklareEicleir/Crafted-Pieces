<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index('user_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index('user_id');
        });

        $fallbackCustomerId = DB::table('users')
            ->where('email', 'customer@craftedpieces.local')
            ->value('id');

        $orders = DB::table('orders')->select('id', 'customer_email')->get();
        foreach ($orders as $order) {
            $matchedUserId = DB::table('users')
                ->where('email', $order->customer_email)
                ->value('id');

            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'user_id' => $matchedUserId ?? $fallbackCustomerId,
                ]);
        }

        if ($fallbackCustomerId) {
            DB::table('quotations')
                ->whereNull('user_id')
                ->update(['user_id' => $fallbackCustomerId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
