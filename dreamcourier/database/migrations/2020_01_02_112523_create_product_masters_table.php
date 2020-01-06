<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->datetime('sales_period_from');
            $table->datetime('sales_period_to');
            $table->string('product_name');
            $table->text('product_description');    #65535文字
            $table->integer('product_price');
            $table->binary('product_image');
            $table->binary('product_thumbnail');
            $table->string('product_search_keyword');
            $table->string('product_tag');
            $table->integer('product_stock_quantity');
            $table->string('status');
            $table->string('selling_discontinued_classification');
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
        Schema::dropIfExists('product_masters');
    }
}
