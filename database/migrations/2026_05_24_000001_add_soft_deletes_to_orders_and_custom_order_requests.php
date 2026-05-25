<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'deleted_at')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->softDeletes();
            });
        }

        if (! Schema::hasColumn('custom_order_requests', 'deleted_at')) {
            Schema::table('custom_order_requests', function (Blueprint $table): void {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'deleted_at')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('custom_order_requests', 'deleted_at')) {
            Schema::table('custom_order_requests', function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }
    }
};
