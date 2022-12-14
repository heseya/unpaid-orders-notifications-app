<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitSettings extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->dateTime('products_updated_at')->nullable();
            $table->dateTime('products_private_updated_at')->nullable();
            $table->dateTime('orders_updated_at')->nullable();
            $table->dateTime('items_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->dropColumn([
                'products_updated_at',
                'products_private_updated_at',
                'orders_updated_at',
                'items_updated_at',
            ]);
        });
    }
}
