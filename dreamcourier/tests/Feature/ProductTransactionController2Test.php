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

class ProductTransactionController2Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 購入手続き：お届け先、連絡先の入力画面テスト
     *
     * @return void
     * @dataProvider dataproviderProductTransactionController2
     */
    public function testProductTransactionController2($query, $expect)
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

        #'address_select' => ['required',Rule::in('登録済み住所','個別指定住所')],
        #'phone_select' => ['required',Rule::in('登録済み電話番号','個別指定電話番号')],
        $nomal_query = [
            'address_select'=>'個別指定住所',
            'phone_select'=>'個別指定電話番号',
            'receiver_name'=>'夢の中　花子',
            'postal_code1'=>'134',
            'postal_code2'=>'0083',
            'address1'=>'東京都',
            'address2'=>'江戸川区',
            'address3'=>'中葛西',
            'address4'=>'４−２−４',
            'address5'=>'マンション名',
            'address6'=>'９９９号',
            'phone_number1'=>'090',
            'phone_number2'=>'1111',
            'phone_number3'=>'2222',
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id='.$cart1['id']);
        $response->assertStatus(200);

        #データプロバイダーより受けたデータでテスト
        $merge_query = array_merge($nomal_query,$query);
        $response = $this->post('/member/delivery_address',$merge_query);

        $response->assertStatus($expect['status']);
        if(empty($expect['error_items'])){                           #正常ケースの場合
            $response->assertSessionHasNoErrors();
            $response->assertRedirect($expect['redirect']);
        }else{                                                       #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_items']);
            $response->assertRedirect($expect['redirect'].$cart1['id']);
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
    public function dataproviderProductTransactionController2()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            't-0'  => ['data'=>[],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #/member/delivery_address?cartlist_id='
            #receiver_name
            't-1-1 receiver_name なし' => [['receiver_name'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['receiver_name' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-2 receiver_name max60' => [['receiver_name'=>'あああああいいいいいうううううえええええおおおおおかかかかかきききききくくくくくけけけけけこここここさささささししししし',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-3 receiver_name max60超' => [['receiver_name'=>'あああああいいいいいうううううえええええおおおおおかかかかかきききききくくくくくけけけけけこここここさささささしししししす',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['receiver_name' => '６０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #postal_code1,2
            't-2-1 postal_code1 required' => [['postal_code1'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['postal_code1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-2 postal_code1 digits 3' => [['postal_code1'=>'1234',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['postal_code1' => '３桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-3 postal_code2 required' => [['postal_code2'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['postal_code2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-4 postal_code2 digits 4' => [['postal_code2'=>'123',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['postal_code2' => '４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #address1,2,3,4
            't-3-1 address1 required' => [['address1'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-2 address2 required' => [['address2'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-3 address3 required' => [['address3'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address3' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-4 address4 required' => [['address4'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address4' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #address5,6
            't-4-1 address5,6 両方なし' => [['address5'=>'','address6'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-2 address5,6 address5のみ' => [['address5'=>'エイトシティ２','address6'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address6' => '部屋番号の入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-3 address5,6 address6のみ' => [['address5'=>'','address6'=>'２０３号室',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address5' => 'マンション名の入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-4 address5,6 両方あり' => [['address5'=>'エイトシティ２','address6'=>'２０３号室',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],

            #phone_number1
            't-5-1 phone_number1 required  ' => [['phone_number1'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-2 phone_number1 max11' => [['phone_number1'=>'11122233344',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-3 phone_number1 max11' => [['phone_number1'=>'111222333444',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number1' => '１〜１１桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #phone_number2
            't-6-1 phone_number2 required  ' => [['phone_number2'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-2 phone_number2 max 4  ' => [['phone_number2'=>'1234',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-3 phone_number2 max 4  ' => [['phone_number2'=>'12345',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number2' => '１〜４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #phone_number3
            't-7-1 phone_number3 required  ' => [['phone_number3'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number3' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-7-2 phone_number3 digits 4  ' => [['phone_number3'=>'123',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_number3' => '４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],

            #address_select
            't-20-1 address_select required  ' => [['address_select'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address_select' => '選択が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-2 address_select in  ' => [['address_select'=>'住所',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['address_select' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-3 address_select in  ' => [['address_select'=>'登録済み住所',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #phone_select
            't-21-1 phone_select required  ' => [['phone_select'=>'',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_select' => '選択が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-21-2 phone_select in  ' => [['phone_select'=>'住所',],
                ['status'=>302,'redirect'=>'/member/delivery_address?cartlist_id=','error_items'=>['phone_select' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-21-3 phone_select in  ' => [['phone_select'=>'登録済み電話番号',],
                ['status'=>302,'redirect'=>'/member/delivery_datetime','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
        ];
    }
}