<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('preferred_size')->nullable()->after('design_theme');
            $table->text('description')->nullable()->after('preferred_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['customer_email', 'preferred_size', 'description']);
        });
    }
};
