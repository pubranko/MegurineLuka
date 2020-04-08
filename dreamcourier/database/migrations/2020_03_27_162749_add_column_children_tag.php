<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChildrenTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('featured_product_masters', function (Blueprint $table) {
            $table->string('children_tag')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_product_masters', function (Blueprint $table) {
            $table->dropColumn(['children_tag']);
        });
    }
}
