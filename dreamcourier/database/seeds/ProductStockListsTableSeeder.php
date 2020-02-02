<?php

use Illuminate\Database\Seeder;

class ProductStockListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_stock_lists')->insert([
            'product_code'=>'akagi-101',
            'product_stock_quantity'=>'100',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_stock_lists')->insert([
            'product_code'=>'akagi-102',
            'product_stock_quantity'=>'100',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_stock_lists')->insert([
            'product_code'=>'washizu-101',
            'product_stock_quantity'=>'100',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_stock_lists')->insert([
            'product_code'=>'washizu-101',
            'product_stock_quantity'=>'100',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_stock_lists')->insert([
            'product_code'=>'reijyo-tashinami-101',
            'product_stock_quantity'=>'100',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);

    }
}
