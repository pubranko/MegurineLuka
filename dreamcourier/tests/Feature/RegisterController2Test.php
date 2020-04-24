<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Member;

class RegisterController2Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * A basic feature test example.
     *
     * @return void
     * * @dataProvider dataproviderRegisterController2
     */
    public function testRegisterController2($query, $expect)
    {
        #$this->withoutExceptionHandling();  #予期せぬエラーが発生した場合、どこで落ちたかのルートを表示してくれるようになる。

        factory(Member::class)->create(['email'=>'aaa@test.com']);

        $session_query = ['email'=>'ex@test.com','last_name'=>'夢の中','first_name'=>'花子','last_name_kana'=>'ユメノナカ','first_name_kana'=>'ハナコ','birthday_era'=>'西暦','birthday_year'=>2001,'birthday_month'=>1,'birthday_day'=>1,'sex'=>'女性','postal_code1'=>'134','postal_code2'=>'0083','address1'=>'東京都','address2'=>'江戸川区','address3'=>'中葛西','address4'=>'４−２−４','address5'=>'マンション名','address6'=>'９９９号','phone_number1'=>'090','phone_number2'=>'1111','phone_number3'=>'2222','wk_birthday_ymd'=>'2001-1-1',];
        $nomal_query = ['email'=>'ex@test.com','password'=>'11112222','password_confirmation'=>'11112222'];
        $response = $this->get('/member/register/in');
        $response->assertStatus(200);

        #データプロバイダーより受けたデータでテスト
        $merge_query = array_merge($nomal_query,$query);
        #$response = $this->post('/member/register/check',$merge_query);  #uliにクエリーを結合
        $response = $this->withSession(['register_in_request'=>$session_query])->post('/member/register',$merge_query);

        $response->assertStatus($expect['status']);
        #$response->assertRedirect($expect['redirect']);
        if(empty($expect['error_items'])){                                  #正常ケースの場合
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('members', ['email'=>'ex@test.com']);  #DBに追加されたことを確認
        }else{                                                              #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_items']);
        }
        if(!empty($expect['search_items'])){                                #表示される文字列の確認がある場合
            $response->assertSeeInOrder($expect['search_items']);
        }
        if(!empty($expect['not_search_items'])){                            #表示されない文字列の確認がある場合
            foreach($expect['not_search_items'] as $not_search){
                $response->assertDontSee($not_search);
            }
        }
    }
    /** データプロバイダー */
    public function dataproviderRegisterController2()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            't-0'  => ['data'=>[],
                ['status'=>200,'redirect'=>'','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #email
            't-1-1 email　重複' => [['email'=>'aaa@test.com'],
                ['status'=>302,'redirect'=>'','error_items'=>['email' => '既に登録されているメールアドレスです。',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #password , password_confirmation
            't-2-1 password　なし' => [['password'=>''],
                ['status'=>302,'redirect'=>'','error_items'=>['password' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-2 password　min 7' => [['password'=>'1234567'],
                ['status'=>302,'redirect'=>'','error_items'=>['password' => '８文字以上のパスワードを指定してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-3 password　same' => [['password'=>'33334444'],
                ['status'=>302,'redirect'=>'','error_items'=>['password' => 'パスワード、パスワード再入力の値が異なります。',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-4 password　なし' => [['password_confirmation'=>''],
                ['status'=>302,'redirect'=>'','error_items'=>['password_confirmation' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
        ];
    }
}
