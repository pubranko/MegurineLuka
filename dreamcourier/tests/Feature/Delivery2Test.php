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

class Delivery2Test extends TestCase
{
    use RefreshDatabase;

    #public $first_flg = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
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

        #①カートに商品を追加
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=1'); #商品ID
        $response->assertStatus(302);
        $this->assertDatabaseHas('product_cart_lists', ['id'=>1]);  #DBに追加されたことを確認
        #③カート一覧で購入手続きを行う商品を選択
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=1'); #存在するカートリストID
        $response->assertStatus(200);

        #④購入手続き_配達先指定で、配達先を指定。
        #正常データの雛形１
        $data1 = ['address_select'=>'登録済み住所',
                    'receiver_name'=>'','postal_code1'=>'','postal_code2'=>'','address1'=>'','address2'=>'','address3'=>'','address4'=>'','address5'=>'','address6'=>'',
                    'phone_select'=>'登録済み電話番号',
                    'phone_number1'=>'','phone_number2'=>'','phone_number3'=>'',];
        #正常データの雛形２
        $data2 = [
            'address_select'=>'個別指定住所',
            'receiver_name'=>'テスト　太郎','postal_code1'=>'134','postal_code2'=>'0083','address1'=>'東京都','address2'=>'江戸川区','address3'=>'中葛西','address4'=>'４−２−４','address5'=>'エイトシティ２','address6'=>'２０２号室',
            'phone_select'=>'個別指定電話番号',
            'phone_number1'=>'090','phone_number2'=>'1111','phone_number3'=>'2222',];

        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data1);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data2);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先

        ###以下、テスト項目のみ置き換えて実行###
        #--- address_selectが存在しない場合
        $data_temp = $data1;
        unset($data_temp['address_select']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address_selectに不正値
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['address_select'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先

        #--- phone_selectが存在しない場合
        $data_temp = $data1;
        unset($data_temp['phone_select']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_selectが不正値
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_select'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先

        #--- receiver_nameが存在しない
        $data_temp = $data2;
        unset($data_temp['receiver_name']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- receiver_nameが空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['receiver_name'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- receiver_name：桁数MAX
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,[
            'receiver_name'=>'あああああいいいいいうううううえええええおおおおおあああああいいいいいうううううえええええおおおおおかかかかかききききき'
            ]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先
        #--- receiver_name：桁数MAXオーバー
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,[
            'receiver_name'=>'あああああいいいいいうううううえええええおおおおおあああああいいいいいうううううえええええおおおおおかかかかかきききききく'
            ]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code1 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['postal_code1']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code1 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['postal_code1'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code1 ：桁不足
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['postal_code1'=>69]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code2 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['postal_code2']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code2 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['postal_code2'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- postal_code2 ：桁不足
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['postal_code2'=>815]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address1 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['address1']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address1 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['address1'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address2 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['address2']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address2 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['address2'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address3 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['address3']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address3 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['address3'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address4 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['address4']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- address4 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['address4'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先

        #--- phone_number1 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['phone_number1']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number1 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number1'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number1 ：桁max
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number1'=>12345678901]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先
        #--- phone_number1 ：桁maxオーバー
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number1'=>123456789012]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number2 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['phone_number2']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number2 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number2'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number2 ：桁max
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number2'=>1234]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先
        #--- phone_number2 ：桁maxオーバー
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number2'=>12345]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number3 ： 存在しない
        $data_temp = $data2;
        unset($data_temp['phone_number3']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number3 ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number3'=>'']));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
        #--- phone_number3 ：桁エラー
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',array_merge($data2,['phone_number3'=>123]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_address?cartlist_id=1");  #バリデーションエラー時のリダイレクト先
    }
}
/*
以下のリクエストのテスト
ProductShowRequest	        #DB使用
ProductCartAddRequest	    #DB使用
ProductCartDeleteRequest	#DB使用
ProductCartSelectRequest	#DB使用
DeliveryAddressCheckRequest
DeliveryDatetimeCheckRequest
DeliveryPaymentCheckRequest
DeliveryRegisterRequest	    #DB使用
*/