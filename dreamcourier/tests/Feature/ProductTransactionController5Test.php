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

class ProductTransactionController5Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 購入手続き：確認
     * 独自ルール：PaymentStatusUnsettledRuleのテスト
     *
     * @return void
     */
    public function testPaymentStatusUnsettledRule()
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        #ログインユーザーの指定
        $user = factory(Member::class)->create();
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
            'payment_status'=>'決済',                   #決済済みのデータ
        ]);

        #テストの基準日時設定
        Carbon::setTestNow(Carbon::parse('2020-04-23 10:00:00'));

        #--正常パターンのセッション値
        $nomal_session=['cartLists'=>['cartlist_id'=>$cart1['id']],
            'items'=>[
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
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                    'card_security_code' => '777',],
            ],
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->withSession($nomal_session)->get('/member/delivery_check');
        $response->assertStatus(200);

        #エラーケース
        $merge_session = array_merge($nomal_session,['cartLists'=>['cartlist_id'=>$cart2['id']]]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(302);
        $response->assertRedirect('/member/delivery_check');
        $response->assertSessionHasErrors(['cartlist_id'=>'購入手続き中であったカートの商品が、既に決済済み、またはキャンセルされていたため決済処理を中止しました。']);
        #正常ケース
        $merge_session = array_merge($nomal_session,[]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        #$response->assertRedirect('/member/delivery_register');
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_transaction_lists', ['member_code'=>$user['member_code'],'product_code'=>$product1->product_code]);
        $this->assertDatabaseHas('product_delivery_status_lists', ['delivery_status' => '配達準備中']);
        $this->assertDatabaseHas('product_cart_lists', ['payment_status' => '決済']);
        $this->assertDatabaseHas('product_stock_lists', ['product_code'=>$product1->product_code,'product_stock_quantity'=>9]);  #在庫が10->9へ減算
    }
    /**
     * 購入手続き：確認
     * 独自ルール：SellingDiscontinuedRuleのテスト
     *
     * @return void
     */
    public function testSellingDiscontinuedRule()
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        #ログインユーザーの指定
        $user = factory(Member::class)->create();
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
            'selling_discontinued_classification'=>'販売中止',                      #販売中止のデータ
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

        #テストの基準日時設定
        Carbon::setTestNow(Carbon::parse('2020-04-23 10:00:00'));

        #--正常パターンのセッション値
        $nomal_session=['cartLists'=>['cartlist_id'=>$cart1['id']],
            'items'=>[
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
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                    'card_security_code' => '777',],
            ],
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->withSession($nomal_session)->get('/member/delivery_check');
        $response->assertStatus(200);

        #エラーケース
        $merge_session = array_merge($nomal_session,['items'=>['wk_product'=>['product_code'=>$product2->product_code,]]]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(302);
        $response->assertRedirect('/member/delivery_check');
        $response->assertSessionHasErrors(['product_code'=>'この商品は、現在販売を中止させて頂いております。']);
        #正常ケース
        $merge_session = array_merge($nomal_session,[]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        #$response->assertRedirect('/member/delivery_register');
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_transaction_lists', ['member_code'=>$user['member_code'],'product_code'=>$product1->product_code]);
        $this->assertDatabaseHas('product_delivery_status_lists', ['delivery_status' => '配達準備中']);
        $this->assertDatabaseHas('product_cart_lists', ['payment_status' => '決済']);
        $this->assertDatabaseHas('product_stock_lists', ['product_code'=>$product1->product_code,'product_stock_quantity'=>9]);  #在庫が10->9へ減算
    }
    /**
     * 購入手続き：確認
     * 独自ルール：ProductStockRuleのテスト
     *
     * @return void
     */
    public function testProductStockRule()
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        #ログインユーザーの指定
        $user = factory(Member::class)->create();
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
            'product_stock_quantity' => 0,              #在庫ゼロ
        ]);
        $cart1 = factory(ProductCartList::class)->create([
            'product_id'=>$product1['id'],
            'member_code'=>$user['member_code'],
            'payment_status'=>'未決済',
        ]);

        #テストの基準日時設定
        Carbon::setTestNow(Carbon::parse('2020-04-23 10:00:00'));

        #--正常パターンのセッション値
        $nomal_session=['cartLists'=>['cartlist_id'=>$cart1['id']],
            'items'=>[
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
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                    'card_security_code' => '777',],
            ],
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user,'member')->withSession($nomal_session)->get('/member/delivery_check');
        $response->assertStatus(200);

        #エラーケース
        $merge_session = array_merge($nomal_session,['items'=>['wk_product'=>['product_code'=>$product2->product_code,]]]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(302);
        $response->assertRedirect('/member/delivery_check');
        $response->assertSessionHasErrors(['product_code'=>'商品の在庫がなくなったたため、決済処理がキャンセルされました。']);
        #正常ケース
        $merge_session = array_merge($nomal_session,[]);
        $response = $this->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        #$response->assertRedirect('/member/delivery_register');
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_transaction_lists', ['member_code'=>$user['member_code'],'product_code'=>$product1->product_code]);
        $this->assertDatabaseHas('product_delivery_status_lists', ['delivery_status' => '配達準備中']);
        $this->assertDatabaseHas('product_cart_lists', ['payment_status' => '決済']);
        $this->assertDatabaseHas('product_stock_lists', ['product_code'=>$product1->product_code,'product_stock_quantity'=>9]);  #在庫が10->9へ減算
    }
    /**
     * 購入手続き：確認
     * 独自ルール：MemberPurchaseStopDivisionRuleのテスト
     *
     * @return void
     */
    public function testMemberPurchaseStopDivisionRule()
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        #ログインユーザーの指定
        $user1 = factory(Member::class)->create();
        $user2 = factory(Member::class)->create(['purchase_stop_division' => '購入停止',]);
        #商品マスタのテストデータ生成
        $product1 = factory(ProductMaster::class)->create([
            'product_code' => 'akagi-001',
            'product_tag'=>'アカギ',
            'product_search_keyword'=>'アカギ akagi',
        ]);
        #商品在庫リストのテストデータ生成
        factory(ProductStockList::class)->create([
            'product_code'=>$product1['product_code'],
            'product_stock_quantity' => 10,
        ]);
        $cart1 = factory(ProductCartList::class)->create([
            'product_id'=>$product1['id'],
            'member_code'=>$user1['member_code'],
            'payment_status'=>'未決済',
        ]);

        #テストの基準日時設定
        Carbon::setTestNow(Carbon::parse('2020-04-23 10:00:00'));

        #--正常パターンのセッション値
        $nomal_session=['cartLists'=>['cartlist_id'=>$cart1['id']],
            'items'=>[
                'wk_product'=>[
                    'wk_product_thumbnail'=>'storage/product_thumbnail/ComingSoon.jpg',
                    'product_code'=>$product1->product_code,'product_name'=>$product1->product_name,'product_price'=>$product1->product_price,
                    'wk_product_stock_quantity_status'=>'在庫あり',],
                'wk_delivery_destination'=>[
                    'receiver_name'=>$user1->first_name.'　'.$user1->last_name,'postal_code1'=>$user1->postal_code1,'postal_code2'=>$user1->postal_code2,
                    'address1'=>$user1->address1,'address2'=>$user1->address2,'address3'=>$user1->address3,
                    'address4'=>$user1->address4,'address5'=>$user1->address5,'address6'=>$user1->address6,
                    'phone_number1'=>$user1->address1,'phone_number2'=>$user1->address2,'phone_number3'=>$user1->address3,],
                'wk_datetime'=>[
                    'delivery_date_edit'=>'2100年01月01日','delivery_date'=>'2100-01-01','delivery_time'=>'0:00〜2:00',],
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777','card_month' => '12','card_year' => '77','card_name' => 'TEST tester',
                    'card_security_code' => '777',],
            ],
        ];

        #初期表示（これがないとエラー時のリダイレクト先が'/'になる。
        $response = $this->actingAs($user1,'member')->withSession($nomal_session)->get('/member/delivery_check');
        $response->assertStatus(200);

        #エラーケース
        $merge_session = array_merge($nomal_session,[]);
        $response = $this->actingAs($user2,'member')->withSession($merge_session)->post('/member/delivery_register',[]);    #ログインユーザー：USER2
        $response->assertStatus(302);
        $response->assertRedirect('/member/delivery_check');
        $response->assertSessionHasErrors(['member_code'=>'現在お客様のご購入は、諸事情により停止させて頂いております。']);
        #正常ケース
        $merge_session = array_merge($nomal_session,[]);
        $response = $this->actingAs($user1,'member')->withSession($merge_session)->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        #$response->assertRedirect('/member/delivery_register');
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_transaction_lists', ['member_code'=>$user1['member_code'],'product_code'=>$product1->product_code]);
        $this->assertDatabaseHas('product_delivery_status_lists', ['delivery_status' => '配達準備中']);
        $this->assertDatabaseHas('product_cart_lists', ['payment_status' => '決済']);
        $this->assertDatabaseHas('product_stock_lists', ['product_code'=>$product1->product_code,'product_stock_quantity'=>9]);  #在庫が10->9へ減算
    }
}