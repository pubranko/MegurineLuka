<?php

namespace Tests\Unit;

#use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ProductMaster;
use App\ProductStockList;

/**
 * モデル(ProductMaster)の各メソッドをテスト
 */
class ProductMasterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * scopeSalesPeriodDuplicationCheckメソッドのテスト
     *
     * @dataProvider dataproviderSalesPeriodDuplication
     */
    public function testScopeSalesPeriodDuplicationCheck($pattern, $expect)
    {
        #商品マスタ
        $product = factory(ProductMaster::class)->create([
            'sales_period_from'=>'2020-01-01 00:00:00',     #from≦販売期間＜to：有効範囲 2020/1/1〜2020/1/31
            'sales_period_to'=>'2020-02-1 00:00:00',
        ]);
        $query = ProductMaster::SalesPeriodDuplicationCheck($pattern['from'],$pattern['to'])->get();
        $this->assertEquals($query->count(),$expect);
    }
    /** データプロバイダー
     *  入力の販売期間(from,to),取得されるレコード数
     */
    public function dataproviderSalesPeriodDuplication()
    {
        return [
            'パターン1' => [['from'=>'2019-01-01 00:00:00','to'=>'2020-01-01 00:00:00'],0],
            'パターン2' => [['from'=>'2019-01-01 00:00:00','to'=>'2020-01-01 00:00:01'],1],
            'パターン3' => [['from'=>'2019-01-01 00:00:00','to'=>'2020-01-01 00:00:02'],1],
            'パターン4' => [['from'=>'2020-01-31 23:59:59','to'=>'2020-02-01 00:00:02'],1],
            'パターン5' => [['from'=>'2020-02-01 00:00:00','to'=>'2020-02-01 00:00:02'],0],
            'パターン6' => [['from'=>'2020-02-01 00:00:01','to'=>'2020-02-01 00:00:02'],0],
            'パターン7' => [['from'=>'2019-12-31 23:59:59','to'=>'2020-02-01 00:00:01'],1],
            'パターン8' => [['from'=>'2020-01-01 00:00:00','to'=>'2020-01-31 23:59:59'],1],
        ];
    }

    /**
     * productStockListメソッドのテスト
     */
    public function testProductStockList()
    {
        #商品マスタ：商品在庫リスト（有り）
        $product = factory(ProductMaster::class)->create([
            'product_code' => 'test-001',
            'sales_period_from'=>'2020-01-01 00:00:00',     #from≦販売期間＜to：有効範囲 2020/1/1〜2020/1/31
            'sales_period_to'=>'2020-02-1 00:00:00',
        ]);
        factory(ProductMaster::class)->create([
            'product_code' => $product['product_code'],
            'sales_period_from'=>'2020-02-01 00:00:00',     #from≦販売期間＜to：有効範囲 2020/2/1〜2020/2/29
            'sales_period_to'=>'2020-03-1 00:00:00',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product['product_code'],
        ]);

        #商品マスタ：商品在庫リスト（なし）
        factory(ProductMaster::class)->create([
            'product_code' => 'test-002',
            'sales_period_from'=>'2020-01-01 00:00:00',     #from≦販売期間＜to：有効範囲 2020/1/1〜2020/1/31
            'sales_period_to'=>'2020-02-1 00:00:00',
        ]);

        #test-001は2件とも商品在庫リストを取得できる。
        $query = ProductMaster::where('product_code','=','test-001')->where('sales_period_from','=','2020-01-01 00:00:00')->first();
        $stock = $query->productStockList;
        $this->assertNotNull($stock);
        $query = ProductMaster::where('product_code','=','test-001')->where('sales_period_from','=','2020-02-01 00:00:00')->first();
        $stock = $query->productStockList;
        $this->assertNotNull($stock);

        #test-002は商品在庫リストが無いので取得できない。
        $query = ProductMaster::where('product_code','=','test-002')->first();
        $stock = $query->productStockList;
        $this->assertNull($stock);
    }

    /**
     * productStockStatusメソッドのテスト
     */
    public function testProductStockStatus()
    {
        #商品在庫リスト（0）
        $product_0 = factory(ProductMaster::class)->create([
            'product_code' => 'test-100',
            'selling_discontinued_classification'=>'販売可',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_0['product_code'],
            'product_stock_quantity'=>0,
        ]);
        $status = ProductMaster::where('product_code','=','test-100')->first()->productStockStatus();
        $this->assertEquals($status,'在庫なし');
        #商品在庫リスト（1）
        $product_1 = factory(ProductMaster::class)->create([
            'product_code' => 'test-101',
            'selling_discontinued_classification'=>'販売可',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_1['product_code'],
            'product_stock_quantity'=>1,
        ]);
        $status = ProductMaster::where('product_code','=','test-101')->first()->productStockStatus();
        $this->assertEquals($status,'在庫あとわずか！');
        #商品在庫リスト（2）
        $product_2 = factory(ProductMaster::class)->create([
            'product_code' => 'test-102',
            'selling_discontinued_classification'=>'販売可',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_2['product_code'],
            'product_stock_quantity'=>2,
        ]);
        $status = ProductMaster::where('product_code','=','test-102')->first()->productStockStatus();
        $this->assertEquals($status,'在庫あとわずか！');
        #商品在庫リスト（3）
        $product_3 = factory(ProductMaster::class)->create([
            'product_code' => 'test-103',
            'selling_discontinued_classification'=>'販売可',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_3['product_code'],
            'product_stock_quantity'=>3,
        ]);
        $status = ProductMaster::where('product_code','=','test-103')->first()->productStockStatus();
        $this->assertEquals($status,'在庫あとわずか！');
        #商品在庫リスト（4）
        $product_4 = factory(ProductMaster::class)->create([
            'product_code' => 'test-104',
            'selling_discontinued_classification'=>'販売可',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_4['product_code'],
            'product_stock_quantity'=>4,
        ]);
        $status = ProductMaster::where('product_code','=','test-104')->first()->productStockStatus();
        $this->assertEquals($status,'在庫あり');
        #商品マスタ（販売中止）
        $product_not = factory(ProductMaster::class)->create([
            'product_code' => 'test-199',
            'selling_discontinued_classification'=>'販売中止',
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product_not['product_code'],
            'product_stock_quantity'=>4,
        ]);
        $status = ProductMaster::where('product_code','=','test-199')->first()->productStockStatus();
        $this->assertEquals($status,'販売中止');
    }

    /**
     * productImagePathメソッドのテスト
     */
    public function testProductImagePath()
    {
        factory(ProductMaster::class)->create([
            'product_code' => 'test-201',
            'product_image'=>'public/image.png',
        ]);
        $query = ProductMaster::where('product_code','=','test-201')->first()->productImagePath();
        $this->assertEquals($query,'/storage/image.png');
    }

    /**
     * productThumbnailPathメソッドのテスト
     */
    public function testProductThumbnailPath()
    {
        factory(ProductMaster::class)->create([
            'product_code' => 'test-301',
            'product_thumbnail'=>'public/thumbnail.png',
        ]);
        $query = ProductMaster::where('product_code','=','test-301')->first()->productThumbnailPath();
        $this->assertEquals($query,'/storage/thumbnail.png');
    }
}