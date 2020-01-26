<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
#以下追加
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Member;
use App\ProductMaster;
use App\ProductStockList;
use App\ProductCartList;
use App\FeaturedProductMaster;

class Delivery5Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        #ログインユーザーの指定
        $user = factory(Member::class)->create(['member_code' => 1,]);
        #エラーユーザー（購入停止）
        $err_user = factory(Member::class)->create(['member_code' => 2,'purchase_stop_division'=>'購入停止']);
        #商品マスタ
        #１件目：正常用
        $product = factory(ProductMaster::class)->create([
            #'id' => 1,
            'product_code' => 'akagi-001',
            'product_tag'=>'ギャンブル',
            'sales_period_from'=>'2020-01-01 00:00:00',
            'sales_period_to'=>'2030-12-31 00:00:00',
            'selling_discontinued_classification'=>'販売可',
        ]);
        #２件目：エラー用(販売中止)
        factory(ProductMaster::class)->create([
            #'id' => 2,
            'product_code' => 'akagi-002',
            'product_tag'=>'ギャンブル',
            'sales_period_from'=>'2020-01-01 00:00:00',
            'sales_period_to'=>'2030-12-31 00:00:00',
            'selling_discontinued_classification'=>'販売中止',
        ]);
        #３件目：エラー用(在庫なし) ※商品在庫リストの２件目と連動
        factory(ProductMaster::class)->create([
            #'id' => 3,
            'product_code' => 'akagi-003',
            'product_tag'=>'ギャンブル',
            'sales_period_from'=>'2020-01-01 00:00:00',
            'sales_period_to'=>'2030-12-31 00:00:00',
            'selling_discontinued_classification'=>'販売可',
        ]);

        #商品在庫リスト
        #１件目：正常用
        factory(ProductStockList::class)->create(['product_code'=>'akagi-001','product_stock_quantity' => 3,]);
        #２件目：エラー用(販売中止)
        factory(ProductStockList::class)->create(['product_code'=>'akagi-002','product_stock_quantity' => 1,]);
        #３件目：エラー用(在庫なし)
        factory(ProductStockList::class)->create(['product_code'=>'akagi-003','product_stock_quantity' => 0,]);

        #商品カートリスト
        #１件目：未決済（正常用）
        factory(ProductCartList::class)->create(['product_id'=>'1','member_code'=>'1','payment_status' => '未決済',]);
        #２件目：キャンセル（エラー用）
        factory(ProductCartList::class)->create(['product_id'=>'1','member_code'=>'1','payment_status' => 'キャンセル',]);
        #３件目：エラー用
        factory(ProductCartList::class)->create(['product_id'=>'2','member_code'=>'1','payment_status' => '未決済',]);
        #４件目：エラー用
        factory(ProductCartList::class)->create(['product_id'=>'3','member_code'=>'1','payment_status' => '未決済',]);

        #①カートに商品を追加
        #(ProductCartAddRequestのバリデーションテスト)
        #$response = $this->actingAs($user,'member')->get('/member/cart_add?id=1'); #商品ID
        #$response->assertStatus(302);
        #$this->assertDatabaseHas('product_cart_lists', ['id'=>1]);  #DBに追加されたことを確認


        #⑥購入手続き_支払い方法指定で、クレジットカード情報を入力
        #--一度配達日時指定画面を表示(エラー時の戻り先の仕込み)
        $response = $this->actingAs($user,'member')->get('/member/delivery_payment');
        $response->assertStatus(200);

        ############################################
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        ############################################

        #⑦購入手続き（結果）
        #--正常パターンのセッション値
        $s=['cartLists'=>['cartlist_id'=>1],
            'items'=>[
                'wk_product'=>[
                    'wk_product_thumbnail'=>'storage/product_thumbnail/ComingSoon.jpg',
                    'product_code'=>$product->product_code,'product_name'=>$product->product_name,'product_price'=>$product->product_price,
                    'wk_product_stock_quantity_status'=>'在庫あり',],
                'wk_delivery_destination'=>[
                    'receiver_name'=>$user->first_name,'postal_code1'=>$user->postal_code1,'postal_code2'=>$user->postal_code2,
                    'address1'=>$user->address1,'address2'=>$user->address2,'address3'=>$user->address3,
                    'address4'=>$user->address4,'address5'=>$user->address5,'address6'=>$user->address6,
                    'phone_number1'=>$user->address1,'phone_number2'=>$user->address2,'phone_number3'=>$user->address3,],
                'wk_datetime'=>[
                    'delivery_date_edit'=>'2100年01月01日','delivery_date'=>'2100-01-01','delivery_time'=>'0:00〜2:00',],
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                    'card_security_code' => '777',],
            ],
        ];

        ### エラーパターン
        #--cartlist_id(payment_status) :キャンセルされていた場合
        $response = $this->actingAs($user,'member')->withSession(array_merge($s,['cartLists'=>['cartlist_id'=>2]]))->post('/member/delivery_register',[]);
        $response->assertStatus(302);

        ############################################
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        ############################################

        #--product_code(selling_discontinued_classification) :商品が販売中止
        $e = $s;
        $e['cartLists']['cartlist_id'] = 3;
        $e['items']['wk_product']['product_code']='akagi-002';
        $response = $this->actingAs($user,'member')->withSession($e)->post('/member/delivery_register',[]);
        $response->assertStatus(302);
        #--product_code(product_stock_quantity) :商品が在庫なし
        $e['cartLists']['cartlist_id'] = 4;
        $e['items']['wk_product']['product_code']='akagi-003';
        $response = $this->actingAs($user,'member')->withSession($e)->post('/member/delivery_register',[]);
        $response->assertStatus(302);

        $response = $this->actingAs($err_user,'member')->withSession(array_merge($s,[]))->post('/member/delivery_register',[]);
        $response->assertStatus(302);

        ### 正常パターン
        $response = $this->actingAs($user,'member')->withSession(array_merge($s,[]))->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('product_transaction_lists', ['id'=>1]);  #DBに追加されたことを確認
        $this->assertDatabaseHas('product_delivery_status_lists', ['id'=>1]);  #DBに追加されたことを確認
        $this->assertDatabaseHas('product_cart_lists', ['id'=>1,'payment_status'=>'決済']);  #決済へ更新されたことを確認
        $this->assertDatabaseHas('product_stock_lists', ['id'=>1,'product_stock_quantity'=>2]);  #在庫が３から２へ減算されたことを確認

    }
}
