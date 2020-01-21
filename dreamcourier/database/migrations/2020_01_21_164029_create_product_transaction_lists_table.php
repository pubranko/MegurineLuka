<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTransactionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transaction_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('transaction_number')->length(12);
            $table->integer('member_code')->length(5);
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('product_price');
            $table->string('receiver_name');
            $table->integer('postal_code1')->length(3);
            $table->integer('postal_code2')->length(4);
            $table->string('address1');
            $table->string('address2');
            $table->string('address3');
            $table->string('address4');
            $table->string('address5');
            $table->string('address6');
            $table->string('phone_number1');
            $table->string('phone_number2');
            $table->string('phone_number3');
            $table->datetime('delivery_date');
            $table->string('delivery_time');
            $table->string('card_number');
            $table->string('billing_status');
            $table->integer('deposit_appropriation');
            $table->string('status');
            $table->string('transaction_status');
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
        Schema::dropIfExists('product_transaction_lists');
    }
}
