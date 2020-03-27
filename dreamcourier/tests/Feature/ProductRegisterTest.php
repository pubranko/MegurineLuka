<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;#追加
use App\ProductMaster;                                 #追加
use Illuminate\Support\Facades\Validator;               #追加
use App\Http\Requests\ProductRegisterCheckRequest;      #追加
use App\Http\Requests\ProductRegisterRequest;           #追加
use Illuminate\Support\Facades\Storage;         #追加
#use App\Rules\SalesPeriodDuplicationRule;   #追加


class ProductRegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     * @dataProvider dataproviderProductRegisterCheckRequest
     */
    public function testProductRegisterCheckRequest($query, $expect)
    {
        $request = new ProductRegisterCheckRequest();
        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();
        unset($rules['product_code']['2']);     //カスタムルール(SalesPeriodDuplicationRule)の引数へ値を渡す方法が不明によりルールより除去してテストを実施
        unset($rules['product_image'],$rules['product_thumbnail']);     //アップロードファイルのバリデーションテスト方法不明によりルールより除去してテスト実施
        $messages = $request->messages();

        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($query, $rules,$messages);

        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataproviderProductRegisterCheckRequest()
    {
        return [
            '正常'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],true,],
            '商品コード なし'  => [["product_code"=>"", #←エラー項目
                                        "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品コード 正規表現エラー1'  => [["product_code"=>"Akagi001", #←エラー項目
                                        "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品コード 正規表現エラー2'  => [["product_code"=>"Akagi-01", #←エラー項目
                                        "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品コード 正規表現エラー3'  => [["product_code"=>"Akagi-0014", #←エラー項目
                                        "product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM dateエラー1'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル",
                                            "sales_period_date_from"=>"2019-02-29", #←エラー項目
                                            "sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM dateエラー2'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル",
                                            "sales_period_date_from"=>"2018-13-01", #←エラー項目
                                            "sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM dateエラー3'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル",
                                            "sales_period_date_from"=>"", #←エラー項目
                                            "sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM dateエラー4'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル",
                                            "sales_period_date_from"=>"aaaaa", #←エラー項目
                                            "sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM timeエラー1'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-11-30",
                                            "sales_period_time_from"=>"25:00", #←エラー項目
                                            "wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM timeエラー2'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01",
                                            "sales_period_time_from"=>"12:60", #←エラー項目
                                            "wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM timeエラー3'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01",
                                            "sales_period_time_from"=>"", #←エラー項目
                                            "wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間FROM timeエラー4'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01",
                                            "sales_period_time_from"=>"aaaa", #←エラー項目
                                            "wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO dateエラー1'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                            "sales_period_date_to"=>"2021-02-29", #←エラー項目
                                            "sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO dateエラー2'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                            "sales_period_date_to"=>"2021-13-30", #←エラー項目
                                            "sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO dateエラー3'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                            "sales_period_date_to"=>"aaaa", #←エラー項目
                                            "sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO timeエラー1'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30",
                                            "sales_period_time_to"=>"25:00", #←エラー項目
                                            "wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO timeエラー2'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30",
                                            "sales_period_time_to"=>"12:60", #←エラー項目
                                            "wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO timeエラー3'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30",
                                            "sales_period_time_to"=>"aaaa", #←エラー項目
                                            "wk_sales_period_to"=>"2020-11-30 12:00",],false,],

            '販売期間TO date&time組み合わせ1'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                                    "sales_period_date_to"=>"", #←エラー項目
                                                    "sales_period_time_to"=>"12:00", #←エラー項目
                                                    "wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO date&time組み合わせ2'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                                    "sales_period_date_to"=>"2020-11-30", #←エラー項目
                                                    "sales_period_time_to"=>"", #←エラー項目
                                                    "wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '販売期間TO date&time組み合わせ3'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00",
                                                    "sales_period_date_to"=>"", #←テスト項目
                                                    "sales_period_time_to"=>"", #←テスト項目
                                                    "wk_sales_period_to"=>"2020-11-30 12:00",],true,],
            /*'販売期間　FROM > TOエラー'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル",
                                            "sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00",
                                            "wk_sales_period_from"=>"2020-11-30 12:01", #←エラー項目
                                            "sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00",
                                            "wk_sales_period_to"=>"2020-11-30 12:00", #←エラー項目
                                            ],false,],*/
            '商品名　なし'    => [["product_code"=>"Akagi-001",
                                    "product_name"=>"", #←エラー項目
                                    "product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品名　max200'  => [["product_code"=>"Akagi-001",
                                    "product_name"=>str_repeat("あ",200), #←テスト項目（正常）
                                    "product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],true,],
            '商品名　max超'   => [["product_code"=>"Akagi-001",
                                    "product_name"=>str_repeat("あ",201), #←エラー項目
                                    "product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品説明　なし'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ",
                                    "product_description"=>"",
                                    "product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品説明　max1500'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ",
                                    "product_description"=>str_repeat("あ",1500), #←テスト項目（正常）
                                    "product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],true,],
            '商品説明　max超'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ",
                                    "product_description"=>str_repeat("あ",1501), #←エラー項目
                                    "product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品価格　なし'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦",
                                    "product_price"=>"", #←エラー項目
                                    "product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品価格　数値以外'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦",
                                    "product_price"=>"a1000", #←エラー項目
                                    "product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ","product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品検索キーワード　なし'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',
                                    "product_search_keyword"=>"", #←エラー項目
                                    "product_tag"=>"akagi　ギャンブル","sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
            '商品タグ　なし'  => [["product_code"=>"Akagi-001","product_name"=>"アカギ","product_description"=>"アカギと対戦","product_price"=>"1000","product_image"=>'sample.jpg',"product_thumbnail"=>'sample.jpg',"product_search_keyword"=>"アカギ　akagiあかぎ",
                                    "product_tag"=>"", #←エラー項目
                                    "sales_period_date_from"=>"2019-12-01","sales_period_time_from"=>"12:00","wk_sales_period_from"=>"2019-12-01 12:00","sales_period_date_to"=>"2020-11-30","sales_period_time_to"=>"12:00","wk_sales_period_to"=>"2020-11-30 12:00",],false,],
        ];
    }
}
