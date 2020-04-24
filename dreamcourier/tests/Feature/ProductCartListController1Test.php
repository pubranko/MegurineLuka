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

class ProductCartListController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * ProductCartListControllerのテスト
     * カートリストへの追加・削除の機能確認
     * @return void
     */
    public function testProductCartListController1()
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
        $cart = factory(ProductCartList::class)->create([   #削除確認用
            'id'=>9000000,
        ]);

        #タグマスタ
        $tag1 = factory(TagMaster::class)->create(['product_tag'=>'ギャンブル','children_tag'=>'アカギ','tag_level'=>1]);
        $tag2 = factory(TagMaster::class)->create(['product_tag'=>'アカギ','children_tag'=>'','tag_level'=>2]);
        $tag3 = factory(TagMaster::class)->create(['product_tag'=>'異世界転生','children_tag'=>'公爵令嬢の嗜み','tag_level'=>1]);
        $tag4 = factory(TagMaster::class)->create(['product_tag'=>'公爵令嬢の嗜み','children_tag'=>'','tag_level'=>2]);

        #非ログイン状態
        $response = $this->get('show?id='.$product2['id']);
        $response->assertStatus(200);
        $response = $this->get('/member/cart_add?id='.$product2['id']); #
        $response->assertStatus(302);

        #①カートへ追加
        #(ProductMasterIdCheckRequestのバリデーションテスト)
        #正常ケース
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id='.$product2['id']); #商品ID
        $response->assertStatus(302);
        $response->assertRedirect('/member/show?id='.$product2['id']);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_cart_lists', ['product_id'=>$product2['id']]);  #DBに追加されたことを確認
        #エラーケース
        $response = $this->actingAs($user,'member')->get('/member/cart_add'); #エラー：商品IDなし
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDがありません',]);
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=99999'); #エラー：存在しない商品ID
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => '存在しないIDです',]);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>99999]);  #DBに追加されていないことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_add?id=a1'); #エラー：数値以外の商品ID
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDが数値以外となっています',]);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>'a1']);  #DBに追加されていないことを確認
        #②カートの削除
        #(CartListIdCheckRequestのバリデーションテスト)
        #エラーケース
        $response = $this->actingAs($user,'member')->get('/member/cart_delete'); #エラー：cartlist_idなし
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => 'IDがありません',]);
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id=99999'); #エラー：存在しないcartlist_id
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => '存在しないIDです',]);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>99999]);  #DBにないことを確認
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id=a1'); #エラー：数値以外のcartlist_id
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cartlist_id' => 'IDが数値以外となっています',]);
        $this->assertDatabaseMissing('product_cart_lists', ['id'=>'a1']);  #DBにないことを確認
        #正常ケース
        $response = $this->actingAs($user,'member')->get('/member/cart_delete?cartlist_id='.$cart['id']); #
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/member/cart_index');
        $this->assertDeleted('product_cart_lists', ['id'=>$cart['id']]);  #DBから削除されたことを確認


    }
}