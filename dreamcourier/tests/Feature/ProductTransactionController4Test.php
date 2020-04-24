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

class ProductTransactionController4Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 購入手続き：支払い方法指定
     *
     * @return void
     * @dataProvider dataproviderProductTransactionController4
     */
    public function testProductTransactionController4($query, $expect)
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

        #テストの基準日時設定
        Carbon::setTestNow(Carbon::parse('2020-04-23 10:00:00'));

        #--正常パターンのセッション値
        $session=['cartLists'=>['cartlist_id'=>$cart1['id']],
            #'items'=>[
                'wk_product'=>[
                    'wk_product_thumbnail'=>'storage/product_thumbnail/ComingSoon.jpg',
                    'product_code'=>$product1->product_code,'product_name'=>$product1->product_name,'product_price'=>$product1->product_price,
                    'wk_product_stock_quantity_status'=>'在庫あり',],
                'wk_delivery_destination'=>[
                    'receiver_name'=>$user->first_name.'　'.$user->last_name,'postal_code1'=>$user->postal_code1,'postal_code2'=>$user->postal_code2,
                    'address1'=>$user->address1,'address2'=>$user->address2,'address3'=>$user->address3,
                    'address4'=>$user->address4,'address5'=>$user->address5,'address6'=>$user->address6,
                    'phone_number1'=>$user->address1,'phone_number2'=>$user->address2,'phone_number3'=>$user->address3,],
                'wk_datetime'=>[
                    'delivery_date_edit'=>'2100年01月01日','delivery_date'=>'2100-01-01','delivery_time'=>'0:00〜2:00',],
                #'wk_credit_card'=>[
                #    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                #    'card_security_code' => '777',],
            #],
        ];

        $nomal_query = [
            'payment_select'=>'個別指定クレジットカード',
            'card_number' => '7777-7777-7777-7777',
            'card_month' => '12',
            'card_year' => '77',
            'card_name' => 'TEST tester',
            'card_security_code' => '777',
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->get('/member/delivery_payment');
        $response->assertStatus(200);

        #データプロバイダーより受けたデータでテスト
        $merge_query = array_merge($nomal_query,$query);
        $response = $this->withSession($session)->post('/member/delivery_payment',$merge_query);

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
    public function dataproviderProductTransactionController4()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            #正常
            't-0'  => ['data'=>[],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #payment_select
            't-1-1 payment_select required'  => ['data'=>['payment_select'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['payment_select'=> '選択が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-2 payment_select in'  => ['data'=>['payment_select'=>'登録済みクレジットカード',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-3 payment_select in'  => ['data'=>['payment_select'=>'個別指定クレジットカード',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-4 payment_select in'  => ['data'=>['payment_select'=>'クレジットカード',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['payment_select'=> '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #card_number
            't-2-1 card_number required'  => ['data'=>['card_number'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_number'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-2 card_number max19'  => ['data'=>['card_number'=>'1111-2222-3333-4444',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-3 card_number max19'  => ['data'=>['card_number'=>'1111-2222-3333-4444-',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_number'=>'１９文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-4 card_number regex'  => ['data'=>['card_number'=>'1111+2222+3333+4444',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_number'=>'数字とハイフン以外が含まれています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-5 card_number regex'  => ['data'=>['card_number'=>'a111-2222-3333-4444',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_number'=>'数字とハイフン以外が含まれています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #card_month
            't-3-1 card_month required'  => ['data'=>['card_month'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_month'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-2 card_month digits'  => ['data'=>['card_month'=>'1',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_month'=>'２桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-3 card_month digits'  => ['data'=>['card_month'=>'001',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_month'=>'２桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-4 card_month regex'  => ['data'=>['card_month'=>'00',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_month'=>'０１〜１２の間で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-5 card_month regex'  => ['data'=>['card_month'=>'13',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_month'=>'０１〜１２の間で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #card_year
            't-4-1 card_year required'  => ['data'=>['card_year'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_year'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-2 card_year digits'  => ['data'=>['card_year'=>'1',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_year'=>'２桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-3 card_year digits'  => ['data'=>['card_year'=>'001',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_year'=>'２桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #card_name
            't-5-1 card_name required'  => ['data'=>['card_name'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_name'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-2 card_name max50'  => ['data'=>['card_name'=>'aaaaabbbbbcccccdddddeeeeeaaaaabbbbbcccccdddddeeeee',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-3 card_name max50超'  => ['data'=>['card_name'=>'aaaaabbbbbcccccdddddeeeeeaaaaabbbbbcccccdddddeeeeeG',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_name'=>'５０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-4 card_name regex'  => ['data'=>['card_name'=>'a-z A-Z 0-9 ,.-/',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-5 card_name regex'  => ['data'=>['card_name'=>'@',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_name'=>'英字(a-z,A-Z)、数字(0-9)、半角スペース( )、カンマ(,)、ピリオド(.)、ハイフン(-)、スラッシュ(/)で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #card_security_code
            't-6-1 card_security_code required'  => ['data'=>['card_security_code'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_security_code'=>'入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-2 card_security_code digits_between'  => ['data'=>['card_security_code'=>'12',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_security_code'=>'３〜４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-3 card_security_code digits_between'  => ['data'=>['card_security_code'=>'123',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-4 card_security_code digits_between'  => ['data'=>['card_security_code'=>'1234',],
                ['status'=>302,'redirect'=>'/member/delivery_check','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-5 card_security_code digits_between'  => ['data'=>['card_security_code'=>'12345',],
                ['status'=>302,'redirect'=>'/member/delivery_payment','error_items'=>['card_security_code'=>'３〜４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
        ];
    }
}