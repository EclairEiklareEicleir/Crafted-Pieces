<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_order_messages', function (Blueprint $table) {
            $table->string('message_type')->default('text')->after('message');
            $table->boolean('is_system')->default(false)->after('message_type');
            $table->json('meta')->nullable()->after('is_system');
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_messages', function (Blueprint $table) {
            $table->dropColumn(['message_type', 'is_system', 'meta']);
        });
    }
};