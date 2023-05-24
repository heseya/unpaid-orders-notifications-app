<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTokenLangth extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->string('licence_key', 1800)->nullable()->change();
            $table->string('integration_token', 1800)->change();
            $table->string('refresh_token', 1800)->change();
            $table->string('uninstall_token', 1800)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}
