<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ProductMaster;                                  #追加
use App\ProductStockList;                                  #追加
use App\Operator;                                       #追加

class ProductReferenceController2Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 商品参照画面のテスト
     *
     * @return void
     */
    public function testExample()
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。
        #ログインユーザーの指定
        $user = factory(Operator::class)->create();
        factory(Operator::class)->create([
            'operator_code'=>'ope2',
            'name'=>'オペレーター名２',
            'email' => 'ope2@ex.com',
        ]);
        factory(Operator::class)->create([
            'operator_code'=>'ope3',
            'name'=>'オペレーター名３',
            'email' => 'ope3@ex.com',
        ]);

        $product = factory(ProductMaster::class)->create([
            'product_code' => 'akagi-001',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3'
        ]);
        factory(ProductStockList::class)->create([
            'product_code'=>'akagi-001',
        ]);

        var_dump($product->id);
        #正常ケース
        $response = $this->actingAs($user,'operator')->get('/operator/product/show?id='.$product->id);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertSeeInOrder(['akagi-001']);

        #エラーケース
        $response = $this->actingAs($user,'operator')->get('/operator/product/show');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDがありません']);
        $response = $this->actingAs($user,'operator')->get('/operator/product/show?id=a');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => 'IDが数値以外となっています']);
        $response = $this->actingAs($user,'operator')->get('/operator/product/show?id=0');
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['id' => '存在しないIDです']);
    }
}
