<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yarn_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hex_color', 20)->nullable();
            $table->string('preview_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_yarn_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('yarn_color_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['product_id', 'yarn_color_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('yarn_color_id')->nullable()->after('product_variant_id')->constrained('yarn_colors')->nullOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('yarn_color_id')->nullable()->after('product_variant_id')->constrained('yarn_colors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('yarn_color_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('yarn_color_id');
        });

        Schema::dropIfExists('product_yarn_color');
        Schema::dropIfExists('yarn_colors');
    }
};
