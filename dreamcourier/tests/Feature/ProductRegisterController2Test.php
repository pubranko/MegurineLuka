<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ProductMaster;                                  #追加
use Illuminate\Http\UploadedFile;                       #追加
use Illuminate\Support\Facades\Storage;                 #追加
use App\Operator;                                       #追加


class ProductRegisterController2Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 商品画像、商品サムネイルの別枠テスト
     * 画像の縦横比、拡張子のテストを行う。
     * @return void
     */
    public function testExample()
    {
        Storage::fake('avatars');
        $file_nomal = UploadedFile::fake()->image('avatar.jpg',640,640);          #縦横比１：１
        $file_error1 = UploadedFile::fake()->image('avatar.jpg',640,639);     #縦横比１：１以外
        $file_error2 = UploadedFile::fake()->image('avatar.text',640,640);     #拡張子（jpg、png、bmp、gif、svg）以外
        $nomal_data = [
            'product_code'=>'akagi-999',
            'sales_period_date_from'=>'2020-01-09',  'sales_period_time_from'=>'23:00',
            'sales_period_date_to'=>'2020-01-10',    'sales_period_time_to'=>'00:00',
            'product_name'=>'アカギ','product_description'=>'アカギと対戦','product_price'=>'1000',
            'product_search_keyword'=>'アカギ　akagi あかぎ','product_tag'=>'akagi　ギャンブル',
            'product_image'=> $file_nomal,'product_thumbnail'=> $file_nomal,
        ];

        #ログインユーザーの指定
        $user = factory(Operator::class)->create();

        #商品マスタのテストデータ生成
        #akagi-999、2020-01-10 00:00〜2020-01-10 01:00のデータを生成
        factory(ProductMaster::class)->create([
            'product_code' => 'akagi-999',
            'sales_period_from'=>'2020-01-10 00:00:00',
            'sales_period_to'=>'2020-01-10 01:00:00',
        ]);
        #初回アクセス：これが無いと、エラー時のリダイレクト先（前画面）が、localhostになってしまう。
        $response = $this->actingAs($user,'operator')->get('/operator/product/register/in');
        $response->assertStatus(200);

        ### 商品画像 ###
        $merge_query = array_merge($nomal_data,['product_image'=>$file_error1]);
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check',$merge_query);
        $response->assertStatus(302);
        $response->assertRedirect('/operator/product/register/in');  #正常時のリダイレクト先
        $response->assertSessionHasErrors(['product_image' => '画像の縦横比は１：１のみ登録可能です',]);

        $merge_query = array_merge($nomal_data,['product_image'=>$file_error2]);
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check',$merge_query);
        $response->assertStatus(302);
        $response->assertRedirect('/operator/product/register/in');  #正常時のリダイレクト先
        $response->assertSessionHasErrors(['product_image' => '画像ファイル（jpg、png、bmp、gif、svg）を指定してください',]);

        ### 商品サムネイル ###
        $merge_query = array_merge($nomal_data,['product_thumbnail'=>$file_error1]);
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check',$merge_query);
        $response->assertStatus(302);
        $response->assertRedirect('/operator/product/register/in');  #正常時のリダイレクト先
        $response->assertSessionHasErrors(['product_thumbnail' => '画像の縦横比は１：１のみ登録可能です',]);

        $merge_query = array_merge($nomal_data,['product_thumbnail'=>$file_error2]);
        $response = $this->actingAs($user,'operator')->post('/operator/product/register/check',$merge_query);
        $response->assertStatus(302);
        $response->assertRedirect('/operator/product/register/in');  #正常時のリダイレクト先
        $response->assertSessionHasErrors(['product_thumbnail' => '画像ファイル（jpg、png、bmp、gif、svg）を指定してください',]);

    }
}
