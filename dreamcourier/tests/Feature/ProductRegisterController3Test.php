<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ProductMaster;                                  #追加
use Illuminate\Http\UploadedFile;                       #追加
use Illuminate\Support\Facades\Storage;                 #追加
use App\Operator;                                       #追加

class ProductRegisterController3Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 商品情報確認画面のテスト
     * @return void
     */
    public function testExample()
    {
        ###################
        # テスト準備
        ###################
        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        factory(ProductMaster::class)->create([
            'product_code' => 'akagi-999',
            'sales_period_from'=>'2020-01-10 00:00:00',
            'sales_period_to'=>'2020-01-10 01:00:00',
        ]);

        $response = $this->actingAs($user,'operator')->get('/operator/product/register/in');
        $response->assertStatus(200);

        $nomal_data = [
            'product_code'=>'akagi-999','product_name'=>'アカギ','product_description'=>'アカギと対戦','product_price'=>'1000','product_search_keyword'=>'アカギ　akagi あかぎ','product_tag'=>'akagi　ギャンブル',
            'sales_period_date_from'=>'2020-01-08','sales_period_time_from'=>'01:00',
            'wk_sales_period_from'=>'2020-01-08 00:00',  #<=wk
            'sales_period_date_to'=>'2020-01-08','sales_period_time_to'=>'02:00',
            'wk_sales_period_to'=>'2020-01-08 02:00',    #<=wk
            'product_image'=> '',
            'wk_product_image_original_filename'=>'',                                            #<=wk
            'wk_product_image_filename'=>'akagi-999_product_image_test.jpg',                        #<=wk
            'wk_product_image_pathname_client'=>'',   #<=wk
            'product_thumbnail'=> '',
            'wk_product_thumbnail_original_filename'=>'',                                        #<=wk
            'wk_product_thumbnail_filename'=>'akagi-999_product_thumbnail_test.jpg',                      #<=wk
            'wk_product_thumbnail_pathname_client'=>'', #<=wk
        ];

        #再テスト時の復元用
        #Storage::move('public/temp/akagi-999_product_image_test.jpg','public/product_image/akagi-999_product_image_test.jpg');
        #Storage::move('public/temp/akagi-999_product_thumbnail_test.jpg','public/product_thumbnail/akagi-999_product_thumbnail_test.jpg');
        #正式に登録済みとなっているテスト用ファイルをtempフォルダへ移動
        Storage::move('public/product_image/akagi-999_product_image_test.jpg', 'public/temp/akagi-999_product_image_test.jpg');
        Storage::move('public/product_thumbnail/akagi-999_product_thumbnail_test.jpg', 'public/temp/akagi-999_product_thumbnail_test.jpg');

        ###################
        # テスト実行
        ###################
        ### 販売期間の重複テスト　重複期間（akagi-999、2020-01-10 00:00〜2020-01-10 01:00）
        $merge_data = array_merge($nomal_data,['sales_period_date_to'=>'2020-01-11','sales_period_time_to'=>'02:00','wk_sales_period_to'=>'2020-01-11 02:00',]);
        $response = $this->actingAs($user,'operator')->withSession(['product_register_in_request' => $merge_data])
            ->post('/operator/product/register',[]);
        $response->assertStatus(302);
        $response->assertRedirect('/operator/product/register/in');  #エラー時のリダイレクト先
        $response->assertSessionHasErrors(['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']);

        #正常パターン
        $response = $this->actingAs($user,'operator')->withSession(['product_register_in_request' => $nomal_data])
            ->post('/operator/product/register',[]);
        $response->assertStatus(200);   #正常時のステータス
        $response->assertSessionHasNoErrors();  #エラーがないことの確認

        $this->assertDatabaseHas('product_masters', ['product_code'=>'akagi-999']);  #DBに追加されたことを確認
        $this->assertDatabaseHas('product_stock_lists', ['product_code'=>'akagi-999','product_stock_quantity'=>0]);  #在庫が０で初期登録されたことを確認

    }
}
