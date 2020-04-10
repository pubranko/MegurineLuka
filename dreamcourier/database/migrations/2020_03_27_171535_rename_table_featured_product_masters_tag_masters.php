<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableFeaturedProductMastersTagMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('featured_product_masters', function (Blueprint $table) {
            Schema::rename('featured_product_masters', 'tag_masters');  //ここをリネーム処理に ←が元の名前、→がリネーム後
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
            Schema::rename('tag_masters', 'featured_product_masters');
        });
    }
}
