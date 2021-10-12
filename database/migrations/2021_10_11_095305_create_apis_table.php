<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("apis", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("url")->unique();
            $table->string("name")->nullable();
            $table->string("version");
            $table->string("licence_key")->nullable();
            $table->string("integration_token");
            $table->string("refresh_token");
            $table->string("uninstall_token")->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("apis");
    }
}
