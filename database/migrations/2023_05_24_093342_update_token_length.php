<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTokenLength extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->text('licence_key')->nullable()->change();
            $table->text("integration_token")->change();
            $table->text("refresh_token")->change();
            $table->string("uninstall_token", 255)->change();
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
