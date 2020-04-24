<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ProductMaster;                                  #追加
use App\ProductStockList;                                  #追加
use App\Operator;                                       #追加

class ProductReferenceController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * オペレーターの商品検索画面のテスト
     *
     * @return void
     * @dataProvider dataproviderProductReferenceController1
     */
    public function testProductReferenceController1($query, $expect)
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。

        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ(雛形)生成
        $product_data=[
            'master' => [
                'product_code' => 'akagi-001',
                'product_tag'=>'ギャンブル',
                'product_search_keyword'=>'',
                'sales_period_from'=>'2020-01-01 00:00:00',
                'sales_period_to'=>'2020-01-02 00:00:00',
                'selling_discontinued_classification'=>'販売可',
                'status' => '正式',
            ],
            'stock' => [
                'product_code'=>'akagi-001',
                'product_stock_quantity' => 1,
            ]
        ];

        factory(ProductMaster::class)->create(array_merge($product_data['master'],[]));
        factory(ProductStockList::class)->create(array_merge($product_data['stock'],[]));

        factory(ProductMaster::class)->create(array_merge($product_data['master'],
            ['product_code' => 'akagi-002','selling_discontinued_classification'=>'販売中止',
            'sales_period_from'=>'2020-01-02 00:00:00','sales_period_to'=>'2020-01-03 00:00:00',]));
        factory(ProductStockList::class)->create(array_merge($product_data['stock'],
            ['product_code' => 'akagi-002','product_stock_quantity' => 2]));

        factory(ProductMaster::class)->create(array_merge($product_data['master'],
            ['product_code' => 'akagi-003','status' => '仮登録',
            'sales_period_from'=>'2020-01-03 00:00:00','sales_period_to'=>'2020-01-03 12:00:00',]));
        factory(ProductStockList::class)->create(array_merge($product_data['stock'],
            ['product_code' => 'akagi-003','product_stock_quantity' => 3]));

        factory(ProductMaster::class)->create(array_merge($product_data['master'],
            ['product_code' => 'akagi-004','status' => '仮変更',
            'sales_period_from'=>'2020-01-03 12:00:00','sales_period_to'=>'2020-01-03 18:00:00',]));
        factory(ProductStockList::class)->create(array_merge($product_data['stock'],
            ['product_code' => 'akagi-004','product_stock_quantity' => 4]));

        $nomal_query = ['product_code'=>'','product_search_keyword'=>'','product_tag'=>'',
                        'product_stock_quantity_from'=>'','product_stock_quantity_to'=>'',
                        'sales_period_date_from'=>'','sales_period_time_from'=>'','sales_period_date_to'=>'','sales_period_time_to'=>'',
                        'product_list_details'=>'20'];

        #初期表示テスト
        #$response = $this->actingAs($user,'operator')->get('/operator/product/search?first_flg=on');
        #$response->assertStatus(200);

        #データプロバイダーより受けたデータでテスト
        $merge_query = array_merge($nomal_query,$query);
        $response = $this->actingAs($user,'operator')->get('/operator/product/search?'.http_build_query($merge_query));  #uliにクエリーを結合

        $response->assertStatus($expect['status']);
        if(empty($expect['error_item'])){                           #正常ケースの場合
            $response->assertSessionHasNoErrors();
        }else{                                                      #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_item']);
        }
        if(!empty($expect['search_item'])){                            #表示される文字列の確認がある場合
            $response->assertSeeInOrder($expect['search_item']);
        }
        if(!empty($expect['not_search_item'])){                        #表示されない文字列の確認がある場合
            foreach($expect['not_search_item'] as $not_search){
                $response->assertDontSee($not_search);
            }
        }
    }
    /** データプロバイダー */
    public function dataproviderProductReferenceController1()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            '正常'  => ['data'=>[],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004',],'not_search_item'=>[],],
            ],
            #表示明細数
            '表示明細数 なし'  => ['data'=>['product_list_details'=>''],
                ['status'=>302,'error_item'=>['product_list_details' => '表示明細数：入力が漏れています'],'search_item'=>['',],'not_search_item'=>[],],
            ],
            '表示明細数 エラー'  => ['data'=>['product_list_details'=>'a'],
                ['status'=>302,'error_item'=>['product_list_details' => '表示明細数：数値で入力してください'],'search_item'=>['',],'not_search_item'=>[],],
            ],
            '表示明細数 正常'  => ['data'=>['product_list_details'=>'3'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002','akagi-003',],'not_search_item'=>[],],
            ],
            #商品在庫数
            '商品在庫数from エラー'  => ['data'=>['product_stock_quantity_from'=>'a'],
                ['status'=>302,'error_item'=>['product_stock_quantity_from' => '商品在庫数（以上）：数値で入力してください'],'search_item'=>['',],'not_search_item'=>[],],
            ],
            '商品在庫数to   エラー'  => ['data'=>['product_stock_quantity_to'=>'a'],
                ['status'=>302,'error_item'=>['product_stock_quantity_to' => '商品在庫数（以下）：数値で入力してください'],'search_item'=>['',],'not_search_item'=>[],],
            ],
            '商品在庫数 from > to エラー'  => ['data'=>['product_stock_quantity_from'=>'3','product_stock_quantity_to'=>'2'],
                ['status'=>302,'error_item'=>['product_stock_quantity_to' => '商品在庫数の範囲が不正です'],'search_item'=>['',],'not_search_item'=>[],],
            ],
            '商品在庫数 from = to 正常'  => ['data'=>['product_stock_quantity_from'=>'2','product_stock_quantity_to'=>'2'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-002',],'not_search_item'=>['akagi-001','akagi-003','akagi-004',],],
            ],
            '商品在庫数 from < to 正常'  => ['data'=>['product_stock_quantity_from'=>'2','product_stock_quantity_to'=>'3'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-002','akagi-003',],'not_search_item'=>['akagi-001','akagi-001',],],
            ],
            '商品在庫数 from のみ　正常'  => ['data'=>['product_stock_quantity_from'=>'3',],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-003','akagi-004',],'not_search_item'=>['akagi-001','akagi-002',],],
            ],
            '商品在庫数 to   のみ　正常'  => ['data'=>['product_stock_quantity_to'=>'2',],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002',],'not_search_item'=>['akagi-003','akagi-004',],],
            ],
            #販売期間FROM 日付、時間
            '販売期間 日付 from 実日付エラー1'  => ['data'=>['sales_period_date_from'=>'2019-2-29'],
                ['status'=>302,'error_item'=>['sales_period_date_from' => '販売期間FROM：日付形式が不正です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 from 実時間エラー2'  => ['data'=>['sales_period_date_from'=>'2020-1-1','sales_period_time_from'=>'00:60'],
                ['status'=>302,'error_item'=>['sales_period_time_from' => '販売期間FROM：時間形式が不正な値です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 from 実時間エラー3'  => ['data'=>['sales_period_date_from'=>'2020-1-1','sales_period_time_from'=>'24:00'],
                ['status'=>302,'error_item'=>['sales_period_time_from' => '販売期間FROM：時間形式が不正な値です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 from エラー（時間のみ指定）'  => ['data'=>['sales_period_time_from'=>'00:00',],
                ['status'=>302,'error_item'=>['sales_period_date_from' => '販売期間FROM：入力が漏れています'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 日付 from 正常1'  => ['data'=>['sales_period_date_from'=>'2020-1-3'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-003','akagi-004',],'not_search_item'=>['akagi-001','akagi-002',],],
            ],
            '販売期間 時間 from 正常2'  => ['data'=>['sales_period_date_from'=>'2020-1-3','sales_period_time_from'=>'11:59'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-003','akagi-004',],'not_search_item'=>['akagi-001','akagi-002',],],
            ],
            '販売期間 時間 from 正常3'  => ['data'=>['sales_period_date_from'=>'2020-1-3','sales_period_time_from'=>'12:00'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-004'],'not_search_item'=>['akagi-001','akagi-002','akagi-003',],],
            ],
            '販売期間 日付 from 正常4（該当なし）'  => ['data'=>['sales_period_date_from'=>'2020-1-4'],
                ['status'=>200,'error_item'=>[],'search_item'=>[],'not_search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004',],],
            ],
            '販売期間 時間 from 正常5（該当なし）'  => ['data'=>['sales_period_date_from'=>'2020-1-3','sales_period_time_from'=>'18:00',],
                ['status'=>200,'error_item'=>[],'search_item'=>[],'not_search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004',],],
            ],
            #販売期間TO 日付、時間
            '販売期間 日付 to 実日付エラー1'  => ['data'=>['sales_period_date_to'=>'2019-2-29'],
                ['status'=>302,'error_item'=>['sales_period_date_to' => '販売期間TO：日付形式が不正です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 to 実時間エラー2'  => ['data'=>['sales_period_date_to'=>'2020-1-1','sales_period_time_to'=>'00:60'],
                ['status'=>302,'error_item'=>['sales_period_time_to' => '販売期間TO：時間形式が不正な値です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 to 実時間エラー3'  => ['data'=>['sales_period_date_to'=>'2020-1-1','sales_period_time_to'=>'24:00'],
                ['status'=>302,'error_item'=>['sales_period_time_to' => '販売期間TO：時間形式が不正な値です'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 時間 to エラー（時間のみ指定）'  => ['data'=>['sales_period_time_to'=>'00:00',],
                ['status'=>302,'error_item'=>['sales_period_date_to' => '販売期間TO：入力が漏れています'],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売期間 日付 to 正常1'  => ['data'=>['sales_period_date_to'=>'2020-1-2'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001',],'not_search_item'=>['akagi-002','akagi-003','akagi-004',],],
            ],
            '販売期間 時間 to 正常2'  => ['data'=>['sales_period_date_to'=>'2020-1-3','sales_period_time_to'=>'12:00'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002','akagi-003'],'not_search_item'=>['akagi-004'],],
            ],
            '販売期間 時間 to 正常3'  => ['data'=>['sales_period_date_to'=>'2020-1-2','sales_period_time_to'=>'23:59'],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002'],'not_search_item'=>['akagi-003','akagi-004',],],
            ],
            '販売期間 日付 to 正常（該当なし）'  => ['data'=>['sales_period_date_to'=>'2019-12-31'],
                ['status'=>200,'error_item'=>[],'search_item'=>[],'not_search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004'],],
            ],
            '販売期間 時間 to 正常4(該当なし)'  => ['data'=>['sales_period_date_to'=>'2020-1-1','sales_period_time_to'=>'00:00'],
                ['status'=>200,'error_item'=>[],'search_item'=>[],'not_search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004'],],
            ],
            #販売状況
            '販売状況 エラー1'  => ['data'=>['selling_discontinued_classification'=>['エラー'],],
                ['status'=>302,'error_item'=>['selling_discontinued_classification.0' => '不正な値が選択されています',],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売状況 エラー2'  => ['data'=>['selling_discontinued_classification'=>['販売可','エラー'],],
                ['status'=>302,'error_item'=>['selling_discontinued_classification.1' => '不正な値が選択されています',],'search_item'=>[],'not_search_item'=>[],],
            ],
            '販売状況 正常1'  => ['data'=>['selling_discontinued_classification'=>['販売可'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-003','akagi-004'],'not_search_item'=>['akagi-002'],],
            ],
            '販売状況 正常2'  => ['data'=>['selling_discontinued_classification'=>['販売中止'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-002'],'not_search_item'=>['akagi-001','akagi-003','akagi-004'],],
            ],
            '販売状況 正常3'  => ['data'=>['selling_discontinued_classification'=>['販売可','販売中止'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004'],'not_search_item'=>[],],
            ],
            #ステータス
            'ステータス エラー1'  => ['data'=>['status'=>['エラー'],],
                ['status'=>302,'error_item'=>['status.0' => '不正な値が選択されています',],'search_item'=>[],'not_search_item'=>[],],
            ],
            'ステータス エラー2'  => ['data'=>['status'=>['正式','エラー'],],
                ['status'=>302,'error_item'=>['status.1' => '不正な値が選択されています',],'search_item'=>[],'not_search_item'=>[],],
            ],
            'ステータス 正常1'  => ['data'=>['status'=>['正式'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002'],'not_search_item'=>['akagi-003','akagi-004'],],
            ],
            'ステータス 正常2'  => ['data'=>['status'=>['仮登録'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-003'],'not_search_item'=>['akagi-001','akagi-002','akagi-004',],],
            ],
            'ステータス 正常3'  => ['data'=>['status'=>['仮変更'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-004'],'not_search_item'=>['akagi-001','akagi-002','akagi-003'],],
            ],
            'ステータス 正常4'  => ['data'=>['status'=>['正式','仮登録','仮変更'],],
                ['status'=>200,'error_item'=>[],'search_item'=>['akagi-001','akagi-002','akagi-003','akagi-004'],'not_search_item'=>[],],
            ],
        ];
    }

}
