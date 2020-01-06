<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            #$table->string('name');
            #$table->decimal('member_code',5)->unique();
            #$table->string('email')->unique();
            #$table->string('password');
            #$table->rememberToken();
            #$table->timestamps();

            #会員基本情報
            $table->integer('member_code')->length(5)->unique();
            #備忘録(外部KEY)　$table->foreign('member_code')->references('member_code')->on('members');   #外部KEY制約 membersのmember_codeとリンク
            $table->string('email');
            $table->string('password');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('last_name_kana');
            $table->string('first_name_kana');
            $table->date('birthday');
            $table->string('sex');
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
            #その他
            $table->datetime('enrollment_datetime');
            $table->string('unsubscribe_reason')->nullable(true); #->default(null);
            #システム制御
            $table->string('status');
            $table->string('purchase_stop_division')->nullable(true);
            $table->string('temporary_update_operator_code')->nullable(true);
            $table->string('temporary_update_approval_operator_code')->nullable(true);
            $table->string('remember_token')->nullable(true);
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
        Schema::drop('members');
    }
}
