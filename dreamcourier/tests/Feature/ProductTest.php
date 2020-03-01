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

class ProductTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * 商品情報登録（入力）画面のテスト
     * @return void
     */
    public function testProductIn(){
        ###################
        # テスト準備
        ###################
        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #statesで、akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        factory(ProductMaster::class,1)->states('SalesPeriodDuplicationCheck')->create();

        $response = $this->actingAs($user,'operator')->get('/operator/product/register/in');
        $response->assertStatus(200);

        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg',640,640);          #縦横比１：１
        $errorfile = UploadedFile::fake()->image('avatar.jpg',640,639);     #縦横比１：１以外

        ###################
        # テスト実行
        ###################

        ### 販売期間の重複テスト　重複期間（akagi-999、2020-01-10 00:00〜2020-01-10 01:00）
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-09",
            "sales_period_time_from"=>"23:00",
            "sales_period_date_to"=>"2020-01-10",   #←接点
            "sales_period_time_to"=>"00:00",        #←接点
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/checkview");  #正常時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-09",
            "sales_period_time_from"=>"23:00",
            "sales_period_date_to"=>"2020-01-10",   #←重複
            "sales_period_time_to"=>"00:01",        #←重複
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-09",
            "sales_period_time_from"=>"23:00",
            "sales_period_date_to"=>"2020-01-10",   #←重複
            "sales_period_time_to"=>"01:00",        #←重複
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-09", #←接点　内包
            "sales_period_time_from"=>"23:59",      #←接点　内包
            "sales_period_date_to"=>"2020-01-10",   #←接点
            "sales_period_time_to"=>"01:01",        #←接点
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-10", #←重複
            "sales_period_time_from"=>"00:00",      #←重複
            "sales_period_date_to"=>"2020-01-10",   #←接点
            "sales_period_time_to"=>"01:01",        #←接点
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-10", #←重複
            "sales_period_time_from"=>"00:59",      #←重複
            "sales_period_date_to"=>"2020-01-10",   #←接点
            "sales_period_time_to"=>"01:01",        #←接点
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-10", #←接点
            "sales_period_time_from"=>"01:00",      #←接点
            "sales_period_date_to"=>"2020-01-10",   #
            "sales_period_time_to"=>"01:01",        #
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/checkview");  #正常時のリダイレクト先


        ###販売期間from > toのエラーテスト
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-11", #from < to
            "sales_period_time_from"=>"01:00",
            "sales_period_date_to"=>"2020-01-11",
            "sales_period_time_to"=>"01:01",
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/checkview");  #正常時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-11", #from = to
            "sales_period_time_from"=>"01:00",
            "sales_period_date_to"=>"2020-01-11",
            "sales_period_time_to"=>"01:00",
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999",
            "sales_period_date_from"=>"2020-01-11", #from > to
            "sales_period_time_from"=>"01:01",
            "sales_period_date_to"=>"2020-01-11",
            "sales_period_time_to"=>"01:00",
            "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル","product_image"=> $file,"product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先


        ###イメージ、サムネイルの有無、比率のエラーテスト
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999","sales_period_date_from"=>"2020-01-12","sales_period_time_from"=>"00:00","sales_period_date_to"=>"2020-01-12","sales_period_time_to"=>"01:00","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル",
            "product_image"=> $errorfile,
            "product_thumbnail"=> $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先

        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check', [
            "product_code"=>"akagi-999","sales_period_date_from"=>"2020-01-12","sales_period_time_from"=>"00:00","sales_period_date_to"=>"2020-01-12","sales_period_time_to"=>"01:00","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル",
            "product_image"=> $file,
            "product_thumbnail"=> $errorfile,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先
    }

    /**
     * 商品情報登録（確認）画面のテスト
     * @return void
     */
    public function testProductCheck(){
        ###################
        # テスト準備
        ###################
        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #statesで、akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        factory(ProductMaster::class,1)->states('SalesPeriodDuplicationCheck')->create();

        $response = $this->actingAs($user,'operator')->get('/operator/product/register/in');
        $response->assertStatus(200);

        #復元用
        #Storage::move("public/temp/akagi-999_product_image_test.jpg","public/product_image/akagi-999_product_image_test.jpg");
        #Storage::move("public/temp/akagi-999_product_thumbnail_test.jpg","public/product_thumbnail/akagi-999_product_thumbnail_test.jpg");
        #テスト用ファイルをproduct_image,product_thumbnailからtempへ移動
        Storage::move('public/product_image/akagi-999_product_image_test.jpg', 'public/temp/akagi-999_product_image_test.jpg');
        Storage::move('public/product_thumbnail/akagi-999_product_thumbnail_test.jpg', 'public/temp/akagi-999_product_thumbnail_test.jpg');

        #$file = Storage::get("public/temp/akagi-999_product_image_test.jpg");
        #Storage::put('public/temp',$file);
        #$file = Storage::get("public/temp/akagi-999_product_thumbnail_test.jpg");
        #Storage::put('public/temp',$file);

        ###################
        # テスト実行
        ###################
        #正常パターン
        $response = $this->actingAs($user,'operator')->withSession([
                "product_register_in_request" => [
                "product_code"=>"akagi-999","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル",
                "sales_period_date_from"=>"2020-01-08","sales_period_time_from"=>"01:00",
                "wk_sales_period_from"=>"2020-01-08 00:00",  #<=wk
                "sales_period_date_to"=>"2020-01-08","sales_period_time_to"=>"02:00",
                "wk_sales_period_to"=>"2020-01-08 02:00",    #<=wk
                "product_image"=> "",
                "wk_product_image_original_filename"=>"",                                            #<=wk
                "wk_product_image_filename"=>"akagi-999_product_image_test.jpg",                        #<=wk
                "wk_product_image_pathname_client"=>"",   #<=wk
                "product_thumbnail"=> "",
                "wk_product_thumbnail_original_filename"=>"",                                        #<=wk
                "wk_product_thumbnail_filename"=>"akagi-999_product_thumbnail_test.jpg",                      #<=wk
                "wk_product_thumbnail_pathname_client"=>"", #<=wk
                ]
            ])->post('/operator/product/register',[]);
        $response->assertStatus(200);   #正常時のステータス

        ###################
        # テスト実行
        ###################

        ### 販売期間の重複テスト　重複期間（akagi-999、2020-01-10 00:00〜2020-01-10 01:00）
        $response = $this->actingAs($user,'operator')->withSession([
                "product_register_in_request" => [
                "product_code"=>"akagi-999","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_search_keyword"=>"アカギ　akagi あかぎ","product_tag"=>"akagi　ギャンブル",
                "sales_period_date_from"=>"2020-01-10","sales_period_time_from"=>"00:00",
                "wk_sales_period_from"=>"2020-01-10 00:00",  #<=wk
                "sales_period_date_to"=>"2020-01-10","sales_period_time_to"=>"01:00",
                "wk_sales_period_to"=>"2020-01-10 01:00",    #<=wk
                "product_image"=> "",
                "wk_product_image_original_filename"=>"",                                            #<=wk
                "wk_product_image_filename"=>"akagi-999_product_image_test.jpg",                        #<=wk
                "wk_product_image_pathname_client"=>"",   #<=wk
                "product_thumbnail"=> "",
                "wk_product_thumbnail_original_filename"=>"",                                        #<=wk
                "wk_product_thumbnail_filename"=>"akagi-999_product_thumbnail_test.jpg",                      #<=wk
                "wk_product_thumbnail_pathname_client"=>"", #<=wk
                ]
            ])->post('/operator/product/register',[]);
        $response->assertStatus(302);
        $response->assertRedirect("/operator/product/register/in");  #エラー時のリダイレクト先
    }

}
