<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretateMemberMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('member_masters', function (Blueprint $table) {
            $table->increments('id');
            #会員基本情報
            $table->decimal('member_code',5);
            #$table->foreign('member_code')->references('member_code')->on('members');
            $table->string('email');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('last_name_kana');
            $table->string('first_name_kana');
            $table->datetime('birthday');
            $table->string('sex');
            $table->decimal('postal_code1',3);
            $table->decimal('postal_code2',4);
            $table->string('address1');
            $table->string('address2');
            $table->string('address3');
            $table->string('address4');
            $table->string('address5');
            $table->string('address6');
            $table->string('phone_number1');
            $table->string('phone_number2');
            $table->string('phone_number3');
            #その他
            $table->datetime('enrollment_datetime');
            $table->string('unsubscribe_reason');
            #システム制御
            $table->string('status');
            $table->string('purchase_stop_division');
            $table->string('temporary_update_operator_code');
            $table->string('temporary_update_approval_operator_code');
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
        //
        Schema::drop('member_masters');
    }
}
