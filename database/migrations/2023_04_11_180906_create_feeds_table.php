<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('api_id');
            $table->string('name', 64);
            $table->string('query', 1000);
            $table->dateTime('refreshed_at')->nullable();
            $table->json('fields');
            $table->timestamps();
        });
        Schema::dropIfExists('settings');
        Schema::table('apis', function (Blueprint $table) {
            $table->dropColumn([
                'products_updated_at',
                'products_private_updated_at',
                'orders_updated_at',
                'items_updated_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
        Schema::table('apis', function (Blueprint $table) {
            $table->dateTime('products_updated_at')->nullable();
            $table->dateTime('products_private_updated_at')->nullable();
            $table->dateTime('orders_updated_at')->nullable();
            $table->dateTime('items_updated_at')->nullable();
        });
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('api_id')->unique();
            $table->string('store_front_url');
            $table->timestamps();

            $table->foreign('api_id')->references('id')->on('apis')->onDelete('cascade');
        });
    }
};
