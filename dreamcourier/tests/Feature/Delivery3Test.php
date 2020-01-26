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

class Delivery3Test extends TestCase
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

        #正常パターン
        $data=[ 'delivery_date'=>'2100-01-01',
                'delivery_time'=>'0:00〜2:00',];
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,[]));
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先

        #エラーパターン
        #--delivery_dateなし
        $data_temp = $data;
        unset($data_temp['delivery_date']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',$data_temp);
        $response->assertStatus(302);
        $response->assertRedirect("/member/delivery_datetime");  #エラー：
        #--delivery_date ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_date'=>'',]));
        $response->assertRedirect("/member/delivery_datetime");  #エラー：
        #--delivery_date ：日付エラー
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_date'=>'2100-02-31',]));
        $response->assertRedirect("/member/delivery_datetime");  #エラー：

        #--delivery_timeなし
        $data_temp = $data;
        unset($data_temp['delivery_time']);
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',$data_temp);
        $response->assertRedirect("/member/delivery_datetime");  #エラー：
        #--delivery_timeなし ：空
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'',]));
        $response->assertRedirect("/member/delivery_datetime");  #エラー：
        #--delivery_timeなし ：時間のパターン
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'0:00',]));
        $response->assertRedirect("/member/delivery_datetime");  #エラー：

        #以下正常パターンの網羅テスト
        #--delivery_time ：
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'0:00〜2:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'2:00〜4:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'4:00〜6:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'6:00〜8:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'8:00〜10:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'10:00〜12:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'12:00〜14:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'14:00〜16:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'16:00〜18:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'18:00〜20:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'20:00〜22:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
        $response = $this->actingAs($user,'member')->post('/member/delivery_datetime',array_merge($data,['delivery_time'=>'22:00〜24:00',]));
        $response->assertRedirect("/member/delivery_payment");  #正常時のリダイレクト先
    }
}
