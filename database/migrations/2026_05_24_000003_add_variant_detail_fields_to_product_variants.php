<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('yarn_color')->nullable()->after('name');
            $table->string('size')->nullable()->after('hex_color');
            $table->string('material')->nullable()->after('size');
            $table->string('design_style')->nullable()->after('material');
            $table->string('set_quantity')->nullable()->after('design_style');
            $table->string('packaging_option')->nullable()->after('set_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'yarn_color',
                'size',
                'material',
                'design_style',
                'set_quantity',
                'packaging_option',
            ]);
        });
    }
};