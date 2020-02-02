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

class Delivery1Test extends TestCase
{
    use DatabaseMigrations;
    #use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDelivery1()
    {

        #ログインユーザーの指定
        $user = factory(Member::class)->create(['member_code' => 1,]);
        #商品マスタのテストデータ生成
        $product = factory(ProductMaster::class)->create([
            'id' => 1,
            'product_code' => 'akagi-001',
            'product_tag'=>'ギャンブル',
            'sales_period_from'=>'2020-01-01 00:00:00',
            'sales_period_to'=>'2030-12-31 00:00:00',
            'selling_discontinued_classification'=>'販売可',
        ]);
        #商品在庫リストのテストデータ生成
        factory(ProductStockList::class)->create([
            'product_code'=>'akagi-001',
            'product_stock_quantity' => 3,
        ]);

        ###非ログイン状態###
        #非会員向けのページへアクセス
        $response = $this->get('');
        $response->assertStatus(200);
        $response = $this->get('tag?tag=ギャンブル');
        $response->assertStatus(200);
        $response = $this->get('keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(200);
        $response = $this->get('show?id=1');
        $response->assertStatus(200);

        #非ログインでメンバーエリアにアクセスしてエラーとなること
        $response = $this->get('/member/home');
        $response->assertStatus(302);
        $response = $this->get('/member/tag?tag=ギャンブル');
        $response->assertStatus(302);
        $response = $this->get('member/keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(302);
        $response = $this->get('member/show?id=1');
        $response->assertStatus(302);
        $response = $this->get('/member/cart_add?id=1'); #
        $response->assertStatus(302);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>1]);  #DBに追加されていないことを確認
        $response = $this->get('/member/cart_index');
        $response->assertStatus(302);
        $response = $this->get('/member/delivery_address?cartlist_id=1'); #存在するカートリストID
        $response->assertStatus(302);

        ###以下ログイン状態###
        #商品閲覧サイト
        $response = $this->actingAs($user,'member')->get('/member/home');
        $response->assertStatus(200);
        $response = $this->actingAs($user,'member')->get('/member/tag?tag=ギャンブル');
        $response->assertStatus(200);
        $response = $this->actingAs($user,'member')->get('member/keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(200);
        #(ProductShowRequestのバリデーションテスト)
        $response = $this->actingAs($user,'member')->get('member/show?id=1');
        $response->assertStatus(200);
        $response = $this->actingAs($user,'member')->get('member/show');    #エラー：IDなし
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('member/show?id=99999');   #エラー：存在しない商品id
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('member/show?id=a1');   #エラー：数値以外のid
        $response->assertStatus(302);

        ###以下、購入手続きの一連の流れを確認
        #①カートに商品を追加・削除
        #(ProductCartAddRequestのバリデーションテスト)
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=1'); #商品ID
        $response->assertStatus(302);
        $this->assertDatabaseHas('product_cart_lists', ['id'=>1]);  #DBに追加されたことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_add'); #エラー：商品IDなし
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=99999'); #エラー：存在しない商品ID
        $response->assertStatus(302);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>99999]);  #DBに追加されていないことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=a1'); #エラー：数値以外の商品ID
        $response->assertStatus(302);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>'a1']);  #DBに追加されていないことを確認
        #(ProductCartDeleteRequestのバリデーションテスト)
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=1'); #商品ID(削除用にカートリストへ追加)
        $this->assertDatabaseHas('product_cart_lists', ['id'=>2]);  #DBに追加されたことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_delete'); #エラー：cartlist_idなし
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id=99999'); #エラー：存在しないcartlist_id
        $response->assertStatus(302);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>99999]);  #DBにないことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id=a1'); #エラー：数値以外のcartlist_id
        $response->assertStatus(302);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>'a1']);  #DBにないことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id=2'); #
        $response->assertStatus(302);
        $this->assertDeleted('product_cart_lists', ['id'=>2]);  #DBから削除されたことを確認

        #②カート一覧を表示
        $response = $this->actingAs($user,'member')->get('/member/cart_index');
        $response->assertStatus(200);

        #③カート一覧で購入手続きを行う商品を選択
        #(ProductCartSelectRequestのバリデーションテスト)
        $response = $this->actingAs($user,'member')->get('/member/delivery_address'); #エラー：cartlist_idなし
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=99999'); #エラー：存在しないcartlist_id
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=a1'); #エラー：数値以外のcartlist_id
        $response->assertStatus(302);
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=1'); #存在するカートリストID
        $response->assertStatus(200);

        #④購入手続き_配達先指定で、配達先を指定。
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',[
            'address_select'=>'登録済み住所',
            'receiver_name'=>'','postal_code1'=>'','postal_code2'=>'','address1'=>'','address2'=>'','address3'=>'','address4'=>'','address5'=>'','address6'=>'',
            'phone_select'=>'登録済み電話番号',
            'phone_number1'=>'','phone_number2'=>'','phone_number3'=>'',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先

        #⑤購入手続き_配達日時指定で、配達日時を指定。
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',[
            'delivery_date'=>'2100-01-01',
            'delivery_time'=>'0:00〜2:00',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先

        #⑥購入手続き_支払い方法指定で、クレジットカード情報を入力
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',[
            'payment_select'=>'登録済みクレジットカード',
            'card_number'=>'','card_month'=>'','card_year'=>'','card_name'=>'','card_security_code'=>'',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先

        ############################################
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        ############################################

        #⑦購入手続き（結果）
        $response = $this->actingAs($user,'member')
        ->withSession([
            'cartLists'=>['cartlist_id'=>1],
            'items'=>[
                'wk_product'=>[
                    'wk_product_thumbnail'=>'storage/product_thumbnail/ComingSoon.jpg',
                    'product_code'=>$product->product_code,
                    'product_name'=>$product->product_name,
                    'product_price'=>$product->product_price,
                    'wk_product_stock_quantity_status'=>'在庫あり',
                    ],
                'wk_delivery_destination'=>[
                    'receiver_name'=>$user->first_name,
                    'postal_code1'=>$user->postal_code1,
                    'postal_code2'=>$user->postal_code2,
                    'address1'=>$user->address1,
                    'address2'=>$user->address2,
                    'address3'=>$user->address3,
                    'address4'=>$user->address4,
                    'address5'=>$user->address5,
                    'address6'=>$user->address6,
                    'phone_number1'=>$user->address1,
                    'phone_number2'=>$user->address2,
                    'phone_number3'=>$user->address3,
                    ],
                'wk_datetime'=>[
                    'delivery_date_edit'=>'2100年01月01日',
                    'delivery_date'=>'2100-01-01',
                    'delivery_time'=>'0:00〜2:00',
                    ],
                'wk_credit_card'=>[
                    'card_number' => '7777-7777-7777-7777',
                    'card_month' => '12',
                    'card_year' => '77',
                    'card_name' => 'TEST tester',
                    'card_security_code' => '777',
                    ],
            ],
        ])
        ->post('/member/delivery_register',[]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('product_transaction_lists', ['id'=>1]);  #DBに追加されたことを確認
        $this->assertDatabaseHas('product_delivery_status_lists', ['id'=>1]);  #DBに追加されたことを確認
        $this->assertDatabaseHas('product_cart_lists', ['id'=>1,'payment_status'=>'決済']);  #決済へ更新されたことを確認
        $this->assertDatabaseHas('product_stock_lists', ['id'=>1,'product_stock_quantity'=>2]);  #在庫が３から２へ減算されたことを確認
    }
}
