<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretateAddressMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('address_masters', function (Blueprint $table) {
            $table->integer('address_code')->length(9);
            $table->integer('ken_id')->length(2);
            $table->integer('city_id')->length(5);
            $table->integer('town_id')->length(9);
            $table->string('zip')->length(8);
            $table->boolean('office_flg');
            $table->boolean('delete_flg');
            $table->string('ken_name',8);
            $table->string('ken_furi',8);
            $table->string('city_name',24);
            $table->string('city_furi',24);
            $table->string('town_name',32);
            $table->string('town_furi',32);
            $table->string('town_memo',16);
            $table->string('kyoto_street',32);
            $table->string('block_name',64);
            $table->string('block_furi',64);
            $table->string('memo',255);
            $table->string('office_name',255);
            $table->string('office_furi',255);
            $table->string('office_address',255);
            $table->integer('new_id')->length(11);
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
        Schema::drop('address_masters');
    }
}
