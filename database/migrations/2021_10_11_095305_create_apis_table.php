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
        Schema::create('apis', function (Blueprint $table): void {
            $table->id();
            $table->string('url')->unique();
            $table->string('name')->nullable();

            $table->string('payment_url', 400)->default('');
            $table->unsignedTinyInteger('orders_from_days')->default(2);

            $table->string('version');
            $table->string('licence_key')->nullable();
            $table->string('integration_token', 1000);
            $table->string('refresh_token', 1000);
            $table->string('uninstall_token', 128)->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
