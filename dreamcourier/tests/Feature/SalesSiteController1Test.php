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

class SalesSiteController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 会員・非会員向けのサイトの表示確認テスト(TOP、タグ別、検索結果のページ、商品の詳細表示ページ)
     *
     * @return void
     */
    public function testSalesSiteController1()
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

        #タグマスタ
        $tag1 = factory(TagMaster::class)->create(['product_tag'=>'ギャンブル','children_tag'=>'アカギ','tag_level'=>1]);
        $tag2 = factory(TagMaster::class)->create(['product_tag'=>'アカギ','children_tag'=>'','tag_level'=>2]);
        $tag3 = factory(TagMaster::class)->create(['product_tag'=>'異世界転生','children_tag'=>'公爵令嬢の嗜み','tag_level'=>1]);
        $tag4 = factory(TagMaster::class)->create(['product_tag'=>'公爵令嬢の嗜み','children_tag'=>'','tag_level'=>2]);

        ###非ログイン状態###
        #非会員向けのページへアクセス
        $response = $this->get('');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code'],$product2['product_code']]); #サイドバーにカテゴリタグが２つ。商品が２つ表示されることを確認
        $response = $this->get('keyword?product_search_tag=ギャンブル');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code']]);
        $response->assertDontSee($product2['product_code']);
        $response = $this->get('keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code']]);
        $response->assertDontSee($product2['product_code']);
        $response = $this->get('show?id='.$product2['id']);
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product2['product_code']]);
        $response->assertDontSee($product1['product_code']);

        #非ログインでメンバーエリアにアクセスしてエラーとなること
        $response = $this->get('/member/home');
        $response->assertStatus(302);
        $response = $this->get('/member/keyword?product_search_tag=ギャンブル');
        $response->assertStatus(302);
        $response = $this->get('/member/keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(302);
        $response = $this->get('/member/show?id='.$product2['id']);
        $response->assertStatus(302);

        ###以下ログイン状態###
        #商品閲覧サイト
        $response = $this->actingAs($user,'member')->get('/member/home');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code'],$product2['product_code']]);
        $response = $this->actingAs($user,'member')->get('/member/keyword?product_search_tag=ギャンブル');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code']]);
        $response->assertDontSee($product2['product_code']);
        $response = $this->actingAs($user,'member')->get('member/keyword?product_search_keyword=アカギ&search=検索');
        $response->assertStatus(200);
        $response->assertSeeInOrder([$tag1['ギャンブル'],$tag3['異世界転生'],$product1['product_code']]);
        $response->assertDontSee($product2['product_code']);

        #(ProductMasterIdCheckRequestのバリデーションテスト)
        #正常ケース
        $response = $this->actingAs($user,'member')->get('member/show?id='.$product2['id']);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        #エラーケース
        $response = $this->actingAs($user,'member')->get('member/show');    #エラー：IDなし
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDがありません',]);
        $response = $this->actingAs($user,'member')->get('member/show?id=999999999');   #エラー：存在しない商品id
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => '存在しないIDです',]);
        $response = $this->actingAs($user,'member')->get('member/show?id=a1');   #エラー：数値以外のid
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDが数値以外となっています',]);
    }
}
