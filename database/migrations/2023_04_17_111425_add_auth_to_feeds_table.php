<?php

declare(strict_types=1);

use App\Enums\AuthType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->string('auth', 16)->default(AuthType::NO->value);
            $table->string('username', 64)->nullable();
            $table->string('password', 64)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->dropColumn(['auth', 'username', 'password']);
        });
    }
};
