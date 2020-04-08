<?php

use Illuminate\Database\Seeder;

class ProductMastersTableSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # factoryを利用
        # 商品情報マスタを２０件生成し、それに紐づく商品在庫リストを生成
        factory(App\ProductMaster::class, 20)
            ->create(['product_tag' => 'テスト'])
            ->each(function ($product) {
                 $product->productStockList()->save(factory(App\ProductStockList::class)->make());
             });
        #factory(App\ProductMaster::class,50)->create(['product_tag' => 'テスト']);
    }
}
