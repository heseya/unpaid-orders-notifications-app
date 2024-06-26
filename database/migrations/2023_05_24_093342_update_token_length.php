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
            $table->text('licence_key')->nullable()->change();
            $table->text('integration_token')->change();
            $table->text('refresh_token')->change();
            $table->string('uninstall_token', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table): void {
            $table->string('licence_key')->nullable()->change();
            $table->string('integration_token', 1000)->change();
            $table->string('refresh_token', 1000)->change();
            $table->string('uninstall_token', 128)->change();
        });
    }
};
