<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ProductMaster;                                  #追加
use Illuminate\Http\UploadedFile;                       #追加
use Illuminate\Support\Facades\Storage;                 #追加
use App\Operator;                                       #追加

class ProductRegisterController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    #use DatabaseMigrations;

    /**
     * オペレーター用：商品登録機能のテスト
     * @return void
     * @dataProvider dataproviderProductRegisterController
     */
    public function testProductRegisterController($query, $expect)
    {
        Storage::fake('avatars');
        $file_nomal = UploadedFile::fake()->image('avatar.jpg',640,640);          #縦横比１：１
        $nomal_data = [
            'product_code'=>'akagi-999',
            'sales_period_date_from'=>'2020-01-09',  'sales_period_time_from'=>'23:00',
            'sales_period_date_to'=>'2020-01-10',    'sales_period_time_to'=>'00:00',
            'product_name'=>'アカギ','product_description'=>'アカギと対戦','product_price'=>'1000',
            'product_search_keyword'=>'アカギ　akagi あかぎ','product_tag'=>'akagi　ギャンブル',
            'product_image'=> $file_nomal,'product_thumbnail'=> $file_nomal,
        ];

        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        factory(ProductMaster::class)->create([
            'product_code' => 'akagi-999',
            'sales_period_from'=>'2020-01-10 00:00:00',
            'sales_period_to'=>'2020-01-10 01:00:00',
        ]);
        #初回アクセス：これが無いと、エラー時のリダイレクト先（前画面）が、localhostになってしまう。
        $response = $this->actingAs($user,'operator')->get('/operator/product/register/in');
        $response->assertStatus(200);

        #データプロバイダーから受けたテストデータ配列をマージしてテスト実行
        $merge_query = array_merge($nomal_data,$query);
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check',$merge_query);

        #テスト結果検証
        $response->assertStatus($expect['status']);
        $response->assertRedirect($expect['redirect']);  #正常時のリダイレクト先
        if(empty($expect['error_item'])){           #正常ケースの場合
            $response->assertSessionHasNoErrors();
        }else{                                      #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_item']);
        }
    }

    /** データプロバイダー */
    public function dataproviderProductRegisterController()
    {
        return [
            #'初回'  => ['first_flg',''],
            '正常'  => [[],                                                                         #上段：ノーマルデータの加工したい項目([key,value])を指定
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],#下段：予想されるテスト結果(ステータス、リダイレクト先、エラー項目([key,value]))を指定
            ],
            ### 商品コード ###
            '商品コード なし'  => [['product_code'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code' => '入力が漏れています',]],
            ],
            '商品コード 正規表現エラー1'  => [['product_code'=>'Akagi001'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code' => '入力パターンが不正です　※例:Syouhin001-003,S10-123',]],
            ],
            '商品コード 正規表現エラー2'  => [['product_code'=>'Akagi-01'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code' => '入力パターンが不正です　※例:Syouhin001-003,S10-123',]],
            ],
            '商品コード 正規表現エラー3'  => [['product_code'=>'Akagi-0014'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code' => '入力パターンが不正です　※例:Syouhin001-003,S10-123',]],
            ],
            ### 販売期間from date ###
            '販売期間FROM dateエラー1'  => [['sales_period_date_from'=>'2019-02-29'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_from' => '日付形式が不正です',]],
            ],
            '販売期間FROM dateエラー2'  => [['sales_period_date_from'=>'2018-13-01'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_from' => '日付形式が不正です',]],
            ],
            '販売期間FROM dateエラー3'  => [['sales_period_date_from'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_from' => '入力が漏れています',]],
            ],
            '販売期間FROM dateエラー4'  => [['sales_period_date_from'=>'aaaaa'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_from' => '日付形式が不正です',]],
            ],
            ### 販売期間from time ###
            '販売期間FROM timeエラー1'  => [['sales_period_time_from'=>'25:00'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_from' => '時間形式が不正な値です',]],
            ],
            '販売期間FROM timeエラー2'  => [['sales_period_time_from'=>'12:60'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_from' => '時間形式が不正な値です',]],
            ],
            '販売期間FROM timeエラー3'  => [['sales_period_time_from'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_from' => '入力が漏れています',]],
            ],
            '販売期間FROM timeエラー4'  => [['sales_period_time_from'=>'aaaa'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_from' => '時間形式が不正な値です',]],
            ],
            ### 販売期間to date ###
            '販売期間TO dateエラー1'  => [['sales_period_date_to'=>'2021-02-29'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_to' => '日付形式が不正です',]],
            ],
            '販売期間TO dateエラー2'  => [['sales_period_date_to'=>'2021-13-30'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_to' => '日付形式が不正です',]],
            ],
            '販売期間TO dateエラー3'  => [['sales_period_date_to'=>'aaaa'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_to' => '日付形式が不正です',]],
            ],
            ### 販売期間to time ###
            '販売期間TO timeエラー1'  => [['sales_period_time_to'=>'25:00'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_to' => '時間形式が不正な値です',]],
            ],
            '販売期間TO timeエラー2'  => [['sales_period_time_to'=>'12:60'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_to' => '時間形式が不正な値です',]],
            ],
            '販売期間TO timeエラー3'  => [['sales_period_time_to'=>'aaaa'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_to' => '時間形式が不正な値です',]],
            ],
            ### 販売期間　from＆toの組み合わせ ###
            '販売期間TO date&time組み合わせ1'  => [['sales_period_date_to'=>'','sales_period_time_to'=>'12:00'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_date_to' => '入力が漏れています']],
            ],
            '販売期間TO date&time組み合わせ2'  => [['sales_period_date_to'=>'2020-11-30','sales_period_time_to'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['sales_period_time_to' => '入力が漏れています',]],
            ],
            '販売期間TO date&time組み合わせ3'  => [['sales_period_date_from'=>'2020-01-11','sales_period_time_from'=>'00:00',   #販売期間が重複しないように操作
                                                    'sales_period_date_to'=>'','sales_period_time_to'=>''],                     #販売期間toが未入力(正常)のテスト
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],
            '販売期間　FROM = TOエラー'  => [['sales_period_date_from'=>'2020-01-11','sales_period_time_from'=>'00:00',
                                              'sales_period_date_to'  =>'2020-01-11','sales_period_time_to'  =>'00:00'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['wk_sales_period_to' => '販売期間の範囲が不正です',]],
            ],
            '販売期間　FROM > TOエラー'  => [['sales_period_date_from'=>'2020-01-11','sales_period_time_from'=>'00:01',
                                              'sales_period_date_to'  =>'2020-01-11','sales_period_time_to'  =>'00:00'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['wk_sales_period_to' => '販売期間の範囲が不正です',]],
            ],
            '販売期間　FROM < TOエラー'  => [['sales_period_date_from'=>'2020-01-11','sales_period_time_from'=>'00:00',
                                              'sales_period_date_to'  =>'2020-01-11','sales_period_time_to'  =>'00:01'],
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],

            ### 販売期間の重複テスト　テーブル側の期間（akagi-999、2020-01-10 00:00〜2020-01-10 01:00）
            '販売期間重複　パターン1'  => [['sales_period_date_from'=>'2020-01-09',
                                            'sales_period_time_from'=>'23:00',
                                            'sales_period_date_to'=>'2020-01-10',   #←接点
                                            'sales_period_time_to'=>'00:00'],       #←接点
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],
            '販売期間重複　パターン2'  => [['sales_period_date_from'=>'2020-01-09',
                                            'sales_period_time_from'=>'23:00',
                                            'sales_period_date_to'=>'2020-01-10',   #←重複
                                            'sales_period_time_to'=>'00:01',],      #←重複
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']],
            ],
            '販売期間重複　パターン3'  => [['sales_period_date_from'=>'2020-01-09',
                                            'sales_period_time_from'=>'23:00',
                                            'sales_period_date_to'=>'2020-01-10',   #←重複
                                            'sales_period_time_to'=>'01:00',],      #←重複
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']],
            ],
            '販売期間重複　パターン4'  => [['sales_period_date_from'=>'2020-01-09', #←接点　内包
                                            'sales_period_time_from'=>'23:59',      #←接点　内包
                                            'sales_period_date_to'=>'2020-01-10',   #←接点
                                            'sales_period_time_to'=>'01:01',],      #←接点
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']],
            ],
            '販売期間重複　パターン5'  => [['sales_period_date_from'=>'2020-01-10', #←重複
                                            'sales_period_time_from'=>'00:00',      #←重複
                                            'sales_period_date_to'=>'2020-01-10',   #←接点
                                            'sales_period_time_to'=>'01:01',],      #←接点
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']],
            ],
            '販売期間重複　パターン6'  => [['sales_period_date_from'=>'2020-01-10', #←重複
                                            'sales_period_time_from'=>'00:59',      #←重複
                                            'sales_period_date_to'=>'2020-01-10',   #←接点
                                            'sales_period_time_to'=>'01:01',],      #←接点
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_code'=>'同一商品コードで、販売期間が重複するレコードがあります']],
            ],
            '販売期間重複　パターン7'  => [['sales_period_date_from'=>'2020-01-10', #←接点
                                            'sales_period_time_from'=>'01:00',      #←接点
                                            'sales_period_date_to'=>'2020-01-10',   #
                                            'sales_period_time_to'=>'01:01',],      #
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],
            ### 商品名 ###
            '商品名　なし'    => [['product_name'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_name' => '入力が漏れています',]],
            ],
            '商品名　max200'  => [[ 'product_name'=>str_repeat('あ',200)],
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],
            '商品名　max超'   => [['product_name'=>str_repeat('あ',201)],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_name' => '２００文字まで入力可能です',]],
            ],
            ### 商品説明 ###
            '商品説明　なし'  => [['product_description'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_description' => '入力が漏れています',]],
            ],
            '商品説明　max1500'  => [['product_description'=>str_repeat('あ',1500)],
                ['status'=>302,'redirect'=>'/operator/product/register/checkview','error_item'=>[]],
            ],
            '商品説明　max超'  => [['product_description'=>str_repeat('あ',1501)],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_description' => '１５００文字まで入力可能です',]],
            ],
            ### 商品価格 ###
            '商品価格　なし'  => [['product_price'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_price' => '入力が漏れています',]],
            ],
            '商品価格　数値以外'  => [['product_price'=>'a1000'],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_price' => '数値で入力してください',]],
            ],
            ### 商品イメージ ###
            '商品イメージ　なし'  => [['product_image'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_image' => '入力が漏れています',]],
            ],
            #'商品イメージ　拡張子エラー' => 別途テスト
            #'商品イメージ　縦横比エラー'  => 別途テスト
            ### 商品サムネイル ###
            '商品サムネイル　なし'  => [['product_thumbnail'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_thumbnail' => '入力が漏れています',]],
            ],
            #'商品サムネイル　拡張子エラー' => 別途テスト
            #'商品サムネイル　縦横比エラー'  => 別途テスト
            ### 商品検索キーワード ###
            '商品検索キーワード　なし'  => [['product_search_keyword'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_search_keyword' => '入力が漏れています',]],
            ],
            ### 商品タグ ###
            '商品タグ　なし'  => [['product_tag'=>''],
                ['status'=>302,'redirect'=>'/operator/product/register/in','error_item'=>['product_tag' => '入力が漏れています',]],
            ],
        ];
    }
}
