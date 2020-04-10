<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Member;                                         #追加
use Illuminate\Foundation\Testing\DatabaseMigrations;   #追加
use Illuminate\Http\UploadedFile;                       #追加
use Illuminate\Support\Facades\Storage;                 #追加
use App\Operator;                                       #追加
use App\ProductMaster;                                  #追加
use App\ProductStockList;                                  #追加

class ProductSearchTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #statesで、akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        #factory(ProductMaster::class,1)->states('SalesPeriodDuplicationCheck')->create();
        #factory(ProductMaster::class,50)->create();

        #商品マスタのテストデータ生成
        $product = factory(ProductMaster::class)->create([
            'id' => 1,
            'product_code' => 'akagi-001',
            'product_tag'=>'ギャンブル',
            'sales_period_from'=>'2020-01-01 00:00:00',
            'sales_period_to'=>'2030-12-31 00:00:00',
            'selling_discontinued_classification'=>'販売可',
        ]);
        #商品在庫リストのテストデータ生成
        factory(ProductStockList::class)->create([
            'product_code'=>'akagi-001',
            'product_stock_quantity' => 3,
        ]);


        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。

        #初期表示テスト
        $response = $this->actingAs($user,'operator')->get('/operator/product/search?first_flg=on');
        $response->assertStatus(200);

        #無指定検索テスト
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.
            'product_stock_quantity_from=&'.
            'product_stock_quantity_to=&'.
            'sales_period_date_from=&'.
            'sales_period_time_from=&'.
            'sales_period_date_to=&'.
            'sales_period_time_to=&'.
            'product_list_details=20'
        );
        $response->assertStatus(200);

        #商品在庫数テスト
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.
            'product_stock_quantity_from=aaa&'. #←エラー項目
            'product_stock_quantity_to=&'.
            'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.
            'product_stock_quantity_from=&'.
            'product_stock_quantity_to=aaa&'. #←エラー項目
            'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.
            'product_stock_quantity_from=10&'. #←from = to
            'product_stock_quantity_to=10&'.
            'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.'product_list_details=20'
        );
        $response->assertStatus(200);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.
            'product_stock_quantity_from=10&'. #←from > to
            'product_stock_quantity_to=9&'.
            'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.'product_list_details=20'
        );
        $response->assertStatus(302);

        #販売期間FROM〜TOテスト
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=2020-01-10&'.
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(200);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2019-11-31&'.   #←エラー箇所
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=2020-01-10&'.
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=24:00&'.   #←エラー箇所
            'sales_period_date_to=2020-01-11&'.
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=01:60&'.   #←エラー箇所
            'sales_period_date_to=2020-01-11&'.
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=2020-01-32&'.   #←エラー箇所
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=&'.   #←エラー箇所
            'sales_period_time_to=01:00&'. #←エラー箇所
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=2020-01-11&'.
            'sales_period_time_to=24:00&'. #←エラー箇所
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-10&'.
            'sales_period_time_from=00:00&'.
            'sales_period_date_to=2020-01-11&'.
            'sales_period_time_to=01:60&'. #←エラー箇所
            'product_list_details=20'
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.
            'sales_period_date_from=2020-01-11&'.   #エラー箇所　from = to　はエラー
            'sales_period_time_from=01:00&'.
            'sales_period_date_to=2020-01-11&'.
            'sales_period_time_to=01:00&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        #販売状況ステータス
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'selling_discontinued_classification[]=販売可&'.
            'selling_discontinued_classification[]=販売中止&'.
            'product_list_details=20'
        );
        $response->assertStatus(200);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'selling_discontinued_classification[]=あ&'.  #←エラー箇所
            'selling_discontinued_classification[]=販売中止&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        #ステータス
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'status[]=正式&'.
            'status[]=仮登録&'.
            'status[]=仮変更&'.
            'product_list_details=20'
        );
        $response->assertStatus(200);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'status[]=あ&'.     #←エラー箇所
            'status[]=仮登録&'.
            'status[]=仮変更&'.
            'product_list_details=20'
        );
        $response->assertStatus(302);

        #表示明細数
        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'product_list_details=' #←エラー箇所
        );
        $response->assertStatus(302);

        $response = $this->actingAs($user,'operator')->get(
            '/operator/product/search?product_code=akagi&'.'product_search_keyword=&'.'product_tag=&'.'product_stock_quantity_from=&'.'product_stock_quantity_to=&'.'sales_period_date_from=&'.'sales_period_time_from=&'.'sales_period_date_to=&'.'sales_period_time_to=&'.
            'product_list_details=aa' #←エラー箇所
        );
        $response->assertStatus(302);

    }
}
