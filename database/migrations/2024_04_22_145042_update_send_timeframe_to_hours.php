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
        Schema::table('apis', function (Blueprint $table): void {
            $table->unsignedInteger('orders_from_days')->default(48)->change();
        });

        Schema::table('apis', function (Blueprint $table): void {
            $table->renameColumn('orders_from_days', 'orders_from_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table): void {
            $table->unsignedInteger('orders_from_hours')->default(2)->change();
        });

        Schema::table('apis', function (Blueprint $table): void {
            $table->renameColumn('orders_from_hours', 'orders_from_days');
        });
    }
};
