<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Member;
use App\ProductMaster;
use App\ProductStockList;
use App\TagMaster;
use App\ProductCartList;

use Carbon\Carbon;

class ProductTransactionController3Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 購入手続き：お届け日時の入力画面テスト
     *
     * @return void
     * @dataProvider dataproviderProductTransactionController3
     */
    public function testProductTransactionController3($query, $expect)
    {
        #ログインユーザーの指定
        $user = factory(Member::class)->create(['member_code' => 1,]);
        #商品マスタのテストデータ生成
        $product1 = factory(ProductMaster::class)->create([
            'product_code' => 'akagi-001',
            'product_tag'=>'アカギ',
            'product_search_keyword'=>'アカギ akagi',
        ]);
        $product2 = factory(ProductMaster::class)->create([
            'product_code' => 'reijyo-tashinami-101',
            'product_tag'=>'公爵令嬢の嗜み',
            'product_search_keyword'=>'公爵 令嬢 公爵令嬢 嗜み reijyo tashinami',
        ]);
        #商品在庫リストのテストデータ生成
        factory(ProductStockList::class)->create([
            'product_code'=>$product1['product_code'],
            'product_stock_quantity' => 10,
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>$product2['product_code'],
            'product_stock_quantity' => 3,
        ]);
        $cart1 = factory(ProductCartList::class)->create([
            'product_id'=>$product1['id'],
            'member_code'=>$user['member_code'],
            'payment_status'=>'未決済',
        ]);
        $cart2 = factory(ProductCartList::class)->create([
            'product_id'=>$product2['id'],
            'member_code'=>$user['member_code'],
            'payment_status'=>'未決済',
        ]);

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->get('/member/delivery_datetime');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        #テスト用の現在時刻を指定
        Carbon::setTestNow(Carbon::parse($query['now']));

        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',$query['delivery_datetime']);

        $response->assertStatus($expect['status']);
        $response->assertRedirect($expect['redirect']);
        if(empty($expect['error_items'])){                           #正常ケースの場合
            $response->assertSessionHasNoErrors();
        }else{                                                       #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_items']);
        }
        if(!empty($expect['search_items'])){                         #表示される文字列の確認がある場合
            $response->assertSeeInOrder($expect['search_items']);
        }
        if(!empty($expect['not_search_items'])){                     #表示されない文字列の確認がある場合
            foreach($expect['not_search_items'] as $not_search){
                $response->assertDontSee($not_search);
            }
        }
    }
    /** データプロバイダー */
    public function dataproviderProductTransactionController3()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            #delivery_date
            't-1-1 delivery_date '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'','delivery_time'=>'0:00〜2:00']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['delivery_date'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-2 delivery_date '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-31','delivery_time'=>'0:00〜2:00']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['delivery_date'=>'日付形式が不正です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #delivery_time
            't-2-1 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['delivery_time'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-2 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'0:00']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['delivery_time'=>'時間帯指定が不正です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #wk_delivery_datetime
            't-10-1'  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'0:00〜2:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-10-2'  => ['data'=>['now'=>'2020-04-22 12:00:00','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'0:00〜2:00']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['wk_delivery_datetime'=>'配達可能日時は、現時刻より１２時間以降となります',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-10-3'  => ['data'=>['now'=>'2020-04-22 23:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'12:00〜14:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-10-4'  => ['data'=>['now'=>'2020-04-23 00:00:00','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'12:00〜14:00']],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>['wk_delivery_datetime'=>'配達可能日時は、現時刻より１２時間以降となります',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #正常：delivery_timeの１２パターン
            't-20-1 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'0:00〜2:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-2 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'2:00〜4:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-3 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'4:00〜6:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-4 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'6:00〜8:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-5 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'8:00〜10:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-6 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'10:00〜12:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-7 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'12:00〜14:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-8 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'14:00〜16:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-9 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'16:00〜18:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-10 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'18:00〜20:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-11 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'20:00〜22:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-12 delivery_time '  => ['data'=>['now'=>'2020-04-22 11:59:59','delivery_datetime'=>['delivery_date'=>'2020-04-23','delivery_time'=>'22:00〜24:00']],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
        ];
    }
}
