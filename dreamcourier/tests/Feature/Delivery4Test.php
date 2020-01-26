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

class Delivery4Test extends TestCase
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
        #正常データの雛形２
        $data2 = [
            'address_select'=>'個別指定住所',
            'receiver_name'=>'テスト　太郎','postal_code1'=>'134','postal_code2'=>'0083','address1'=>'東京都','address2'=>'江戸川区','address3'=>'中葛西','address4'=>'４−２−４','address5'=>'エイトシティ２','address6'=>'２０２号室',
            'phone_select'=>'個別指定電話番号',
            'phone_number1'=>'090','phone_number2'=>'1111','phone_number3'=>'2222',];
        $response = $this->actingAs($user,'member')->post('/member/delivery_address',$data2);
        $response->assertRedirect("/member/delivery_datetime");  #正常時のリダイレクト先
        #⑤購入手続き_配達日時指定で、配達日時を指定。
        #--一度配達日時指定画面を表示(エラー時の戻り先の仕込み)
        $response = $this->actingAs($user,'member')->get('/member/delivery_datetime');
        $response->assertStatus(200);
        $data=[ 'delivery_date'=>'2100-01-01',
                'delivery_time'=>'0:00〜2:00',];
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,[]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先

        #⑥購入手続き_支払い方法指定で、クレジットカード情報を入力
        #--一度配達日時指定画面を表示(エラー時の戻り先の仕込み)
        $response = $this->actingAs($user,'member')->get('/member/delivery_payment');
        $response->assertStatus(200);

        #正常データの雛形１
        $data1=['payment_select'=>'登録済みクレジットカード',
                'card_number'=>'','card_month'=>'','card_year'=>'','card_name'=>'','card_security_code'=>'',];
        #正常データの雛形２
        $data2=['payment_select'=>'個別指定クレジットカード',
                'card_number'=>'1111-2222-3333-4444','card_month'=>'04','card_year'=>'50','card_name'=>'TEST TAROU','card_security_code'=>'123',];

        #正常データ
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data1,[]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,[]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先

        #エラーパターン
        #--payment_select :なし
        $data_temp = $data2;
        unset($data_temp['payment_select']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--payment_select :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data1,['payment_select'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--payment_select :不正値
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data1,['payment_select'=>'登録済み']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先

        #--card_number :なし
        $data_temp = $data2;
        unset($data_temp['card_number']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_number :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_number'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_number :19桁超
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_number'=>'1111-2222-3333-4444-']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_number :数字・ハイフン以外
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_number'=>'1111/2222/3333/4444']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先

        #--card_month :なし
        $data_temp = $data2;
        unset($data_temp['card_month']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_month :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_month :不正月
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'00']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'13']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'aa']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_month :正常月
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'01']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'09']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'10']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_month'=>'12']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先

        #--card_year :なし
        $data_temp = $data2;
        unset($data_temp['card_year']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_year :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_year'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_year :桁エラー
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_year'=>'0']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_year'=>'100']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_year :数値以外
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_year'=>'aa']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先

        #--card_name :なし
        $data_temp = $data2;
        unset($data_temp['card_name']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_name :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_name'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_name :MAX50
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_name'=>'AAAAABBBBBCCCCCDDDDDEEEEEAAAAABBBBBCCCCCDDDDDEEEEE']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        #--card_name :MAX50超
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_name'=>'AAAAABBBBBCCCCCDDDDDEEEEEAAAAABBBBBCCCCCDDDDDEEEEE-']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_name :使用可能文字
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_name'=>'az AZ 09-,. /']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        #--card_name :使用不可文字
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_name'=>'@ @']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先

        #--card_security_code :なし
        $data_temp = $data2;
        unset($data_temp['card_security_code']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',$data_temp);
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_security_code :空
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_security_code'=>'']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        #--card_security_code :桁3-4以外
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_security_code'=>'12']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_security_code'=>'123']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_security_code'=>'1234']));
        $response->assertRedirect("/member/delivery_check");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_payment',array_merge($data2,['card_security_code'=>'12345']));
        $response->assertRedirect("/member/delivery_payment");  #エラー時のリダイレクト先
    }
}
