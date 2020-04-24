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

class ProductTransactionController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * カートリスト一覧画面のテスト、購入手続きへ進む前のバリデートテスト
     *
     * @return void
     */
    public function testExample()
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

        #タグマスタ
        $tag1 = factory(TagMaster::class)->create(['product_tag'=>'ギャンブル','children_tag'=>'アカギ','tag_level'=>1]);
        $tag2 = factory(TagMaster::class)->create(['product_tag'=>'アカギ','children_tag'=>'','tag_level'=>2]);
        $tag3 = factory(TagMaster::class)->create(['product_tag'=>'異世界転生','children_tag'=>'公爵令嬢の嗜み','tag_level'=>1]);
        $tag4 = factory(TagMaster::class)->create(['product_tag'=>'公爵令嬢の嗜み','children_tag'=>'','tag_level'=>2]);

        #カートリスト一覧
        $response = $this->actingAs($user,'member')->get('/member/cart_index');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertSeeInOrder(['akagi-001','reijyo-tashinami-101',]);

        #購入手続き
        #正常ケース
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id='.$cart1['id']);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        #エラーケース
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => 'IDがありません',]);
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=a');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => 'IDが数値以外となっています',]);
        $response = $this->actingAs($user,'member')->get('/member/delivery_address?cartlist_id=99999999');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => '存在しないIDです',]);

    }
}
