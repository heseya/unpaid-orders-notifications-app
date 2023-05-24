<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleProductFeedSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('product_type_set_parent_filter')->nullable();
            $table->boolean('product_type_set_no_parent_filter')->default(false);
            $table->string('google_custom_label_metatag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('product_type_set_parent_filter');
            $table->dropColumn('product_type_set_no_parent_filter');
            $table->dropColumn('google_custom_label_metatag');
        });
    }
}
