<?php

use App\Models\CustomOrderRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('order_type')->default('online_order')->after('user_id');
            $table->foreignId('custom_order_request_id')
                ->nullable()
                ->unique()
                ->after('order_type')
                ->constrained('custom_order_requests')
                ->nullOnDelete();
        });

        DB::table('orders')->whereNull('order_type')->update(['order_type' => 'online_order']);

        CustomOrderRequest::query()
            ->whereIn('status', [
                CustomOrderRequest::STATUS_AWAITING_PAYMENT,
                CustomOrderRequest::STATUS_PAID,
                CustomOrderRequest::STATUS_IN_PROGRESS,
                CustomOrderRequest::STATUS_COMPLETED,
            ])
            ->orderBy('id')
            ->get()
            ->each(function (CustomOrderRequest $customOrder): void {
                $customOrder->syncLinkedOrder();
            });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('custom_order_request_id');
            $table->dropColumn('order_type');
        });
    }
};