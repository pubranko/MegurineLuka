<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDeliveryStatusListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_delivery_status_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('transaction_number')->length(12);
            $table->string('delivery_status');
            $table->datetime('delivery_status_update_at');
            $table->string('delivery_memo');
            $table->string('status');
            $table->boolean('invalid_flg');
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
        Schema::dropIfExists('product_delivery_status_lists');
    }
}
