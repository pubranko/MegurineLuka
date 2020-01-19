<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedProductMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_product_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('introduction_tag');
            $table->integer('priority');
            $table->string('product_tag');
            $table->datetime('validity_period_from');
            $table->datetime('validity_period_to');
            $table->string('status');
            $table->string('temporary_updater_operator_code');
            $table->string('temporary_update_approver_operator_code');
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
        Schema::dropIfExists('featured_product_masters');
    }
}
