<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCartListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cart_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');               #商品情報マスタのID
            $table->integer('member_code')->length(5);      #会員情報マスタのメンバーコード
            $table->string('payment_status')->length(5);    #決済ステータス
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
        Schema::dropIfExists('product_cart_lists');
    }
}
