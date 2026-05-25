<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('public_reference')->nullable()->unique()->after('user_id');
            $table->string('guest_session_id')->nullable()->after('public_reference');
        });

        DB::table('orders')->orderBy('id')->get()->each(function ($order): void {
            $reference = 'ORD-' . Str::upper(Str::random(10));

            while (DB::table('orders')->where('public_reference', $reference)->exists()) {
                $reference = 'ORD-' . Str::upper(Str::random(10));
            }

            DB::table('orders')
                ->where('id', $order->id)
                ->update(['public_reference' => $reference]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['public_reference', 'guest_session_id']);
        });
    }
};