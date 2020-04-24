<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Member;                                  #追加

class RegisterController1Test extends TestCase
{
    use RefreshDatabase;    #DBリフレッシュ
    /**
     * 会員登録機能のテスト
     *
     * @return void
     * @dataProvider dataproviderRegisterController1
     */
    public function testRegisterController1($query, $expect)
    {

        factory(Member::class)->create(['email'=>'aaa@test.com']);

        $nomal_query = [
            'email'=>'ex@test.com',
            'last_name'=>'夢の中',
            'first_name'=>'花子',
            'last_name_kana'=>'ユメノナカ',
            'first_name_kana'=>'ハナコ',
            'birthday_era'=>'西暦',
            'birthday_year'=>2001,
            'birthday_month'=>1,
            'birthday_day'=>1,
            'sex'=>'女性',
            'postal_code1'=>'134',
            'postal_code2'=>'0083',
            'address1'=>'東京都',
            'address2'=>'江戸川区',
            'address3'=>'中葛西',
            'address4'=>'４−２−４',
            'address5'=>'マンション名',
            'address6'=>'９９９号',
            'phone_number1'=>'090',
            'phone_number2'=>'1111',
            'phone_number3'=>'2222',
        ];

        $response = $this->get('/member/register/in');
        $response->assertStatus(200);

        #データプロバイダーより受けたデータでテスト
        $merge_query = array_merge($nomal_query,$query);
        #$response = $this->get('/member/register/check?'.http_build_query($merge_query));  #uliにクエリーを結合
        $response = $this->post('/member/register/check',$merge_query);

        $response->assertStatus($expect['status']);
        $response->assertRedirect($expect['redirect']);
        if(empty($expect['error_items'])){                           #正常ケースの場合
            $response->assertSessionHasNoErrors();
        }else{                                                       #エラーケースの場合
            $response->assertSessionHasErrors($expect['error_items']);
        }
        if(!empty($expect['search_items'])){                         #表示される文字列の確認がある場合
            $response->assertSeeInOrder($expect['search_items']);
        }
        if(!empty($expect['not_search_items'])){                     #表示されない文字列の確認がある場合
            foreach($expect['not_search_items'] as $not_search){
                $response->assertDontSee($not_search);
            }
        }
    }
    /** データプロバイダー */
    public function dataproviderRegisterController1()
    {
        #上段：テストに使用するデータ[ノーマルデータの加工したい項目[連想配列]を指定
        #下段：予想されるテスト結果[ステータス、エラー項目[連想配列]、サーチ項目[配列(表示される順序)]、サーチできない項目[配列] ]を指定
        return [
            't-0'  => ['data'=>[],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #email
            't-1-1 email　なし' => [['email'=>''],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['email' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-2 email 形式エラー' => [['email'=>'mikuras2@',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['email' => 'メールアドレスの形式ではありません',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-1-3 email 重複' => [['email'=>'aaa@test.com',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['email' => '既に登録されているアドレスです',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #last_name
            't-2-1 last_name なし' => [['last_name'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['last_name' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-2 last_name max30' => [['last_name'=>'あああああいいいいいうううううえええええおおおおおかかかかか',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-2-3 last_name max30超' => [['last_name'=>'あああああいいいいいうううううえええええおおおおおかかかかかき',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['last_name' => '３０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #first_name
            't-3-1 first_name なし' => [['first_name'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['first_name' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-2 first_name max30　' => [['first_name'=>'あああああいいいいいうううううえええええおおおおおかかかかか',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-3-3 first_name max30超' => [['first_name'=>'あああああいいいいいうううううえええええおおおおおかかかかかき',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['first_name' => '３０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #last_kana_name
            't-4-1 last_kana_name　なし' => [['last_name_kana'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['last_name_kana' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-2 last_kana_name max60　' => [['last_name_kana'=>'アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウ',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-3 last_kana_name max60超' => [['last_name_kana'=>'アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウカ',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['last_name_kana' => '６０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-4-4 last_kana_name regix' => [['last_name_kana'=>'あいう',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['last_name_kana' => 'カタカナで入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #first_kana_name
            't-5-1 first_kana_name なし' => [['first_name_kana'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['first_name_kana' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-2 first_kana_name max60　' => [['first_name_kana'=>'アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウ',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-3 first_kana_name max60超' => [['first_name_kana'=>'アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウカ',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['first_name_kana' => '６０文字まで入力可能です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-5-4 first_kana_name regix   ' => [['first_name_kana'=>'あいう',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['first_name_kana' => 'カタカナで入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],

            #birthday関連：単項目チェック
            #birthday_era
            't-6-1 birthday_era なし' => [['birthday_era'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_era' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-6-2 birthday_era in      ' => [['birthday_era'=>'元禄',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_era' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            ###和暦のチェックは後続の項目関連チェックにて実施する。

            #birthday_year
            't-7-1 birthday_year なし' => [['birthday_year'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_year' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-7-2 birthday_year integer ' => [['birthday_year'=>'a',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_year' => '数値で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #birthday_month
            't-8-1 birthday_month なし' => [['birthday_month'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_month' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-8-2 birthday_month integer     ' => [['birthday_month'=>'a',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_month' => '数値で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-8-3 birthday_month between 1-12' => [['birthday_month'=>0,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_month' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-8-4 birthday_month between 1-12' => [['birthday_month'=>1,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-8-5 birthday_month between 1-12' => [['birthday_month'=>12,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-8-6 birthday_month between 1-12' => [['birthday_month'=>13,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_month' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #birthday_day
            't-9-1 birthday_day required    ' => [['birthday_day'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_day' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-9-2 birthday_day integer     ' => [['birthday_day'=>'b',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_day' => '数値で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-9-3 birthday_day between 1-31' => [['birthday_day'=>0,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_day' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-9-4 birthday_day between 1-31' => [['birthday_day'=>1,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-9-5 birthday_day between 1-31' => [['birthday_day'=>31,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-9-6 birthday_day between 1-31' => [['birthday_day'=>32,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['birthday_day' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #sex
            't-20-1 sex required' => [['sex'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['sex' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-2 sex in     ' => [['sex'=>'男性',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-3 sex in     ' => [['sex'=>'女性',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-20-4 sex in     ' => [['sex'=>'男女',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['sex' => '不正な値です',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #postal_code1,2
            't-21- 1 postal_code1 required' => [['postal_code1'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['postal_code1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-21-2 postal_code1 digits 3' => [['postal_code1'=>'1234',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['postal_code1' => '３桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-21-3 postal_code2 required' => [['postal_code2'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['postal_code2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-21-4 postal_code2 digits 4' => [['postal_code2'=>'123',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['postal_code2' => '４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #address1,2,3,4
            't-22-1 address1 required' => [['address1'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-22-2 address2 required' => [['address2'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-22-3 address3 required' => [['address3'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address3' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-22-4 address4 required' => [['address4'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address4' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #address5,6
            't-23-1 address5,6 両方なし' => [['address5'=>'','address6'=>'',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-23-2 address5,6 address5のみ' => [['address5'=>'エイトシティ２','address6'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address6' => '部屋番号の入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-23-3 address5,6 address6のみ' => [['address5'=>'','address6'=>'２０３号室',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['address5' => 'マンション名の入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-23-4 address5,6 両方あり' => [['address5'=>'エイトシティ２','address6'=>'２０３号室',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],

            #phone_number1
            't-24-1 phone_number1 required  ' => [['phone_number1'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number1' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-24-2 phone_number1 max11' => [['phone_number1'=>'11122233344',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-24-3 phone_number1 max11' => [['phone_number1'=>'111222333444',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number1' => '１〜１１桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #phone_number2
            't-25-1 phone_number2 required  ' => [['phone_number2'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number2' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-25-2 phone_number2 max 4  ' => [['phone_number2'=>'1234',],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-25-3 phone_number2 max 4  ' => [['phone_number2'=>'12345',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number2' => '１〜４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #phone_number3
            't-26-1 phone_number3 required  ' => [['phone_number3'=>'',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number3' => '入力が漏れています',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-26-2 phone_number3 digits 4  ' => [['phone_number3'=>'123',],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['phone_number3' => '４桁で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],

            #birthday関連：項目関連チェック
            #西暦
            't-30-1 birthday　西暦' => [['birthday_era'=>'西暦','birthday_year'=>2020,'birthday_month'=>2,'birthday_day'=>29,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-30-2 birthday　西暦' => [['birthday_era'=>'西暦','birthday_year'=>2020,'birthday_month'=>2,'birthday_day'=>30,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd'=> '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #令和
            't-31-1 令和　birthday' => [['birthday_era'=>'令和','birthday_year'=>1,'birthday_month'=>4,'birthday_day'=>31,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd' => '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-31-2 令和　birthday' => [['birthday_era'=>'令和','birthday_year'=>1,'birthday_month'=>4,'birthday_day'=>30,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(令和1年5月1日〜)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-31-3 令和　birthday' => [['birthday_era'=>'令和','birthday_year'=>1,'birthday_month'=>5,'birthday_day'=>1,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            #平成
            't-32-1 平成　birthday' => [['birthday_era'=>'平成','birthday_year'=>1,'birthday_month'=>2,'birthday_day'=>29,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd' => '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-32-2 平成　birthday' => [['birthday_era'=>'平成','birthday_year'=>1,'birthday_month'=>1,'birthday_day'=>7,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(平成1年1月8日〜平成31年4月30日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-32-3 平成　birthday' => [['birthday_era'=>'平成','birthday_year'=>1,'birthday_month'=>1,'birthday_day'=>8,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-32-4 平成　birthday' => [['birthday_era'=>'平成','birthday_year'=>31,'birthday_month'=>4,'birthday_day'=>30,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-32-5 平成　birthday' => [['birthday_era'=>'平成','birthday_year'=>31,'birthday_month'=>5,'birthday_day'=>1,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(平成1年1月8日〜平成31年4月30日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #昭和
            't-33-1 昭和　birthday' => [['birthday_era'=>'昭和','birthday_year'=>2,'birthday_month'=>4,'birthday_day'=>31,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd' => '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-33-2 昭和　birthday' => [['birthday_era'=>'昭和','birthday_year'=>1,'birthday_month'=>12,'birthday_day'=>24,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(昭和1年12月25日〜昭和64年1月7日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-33-3 昭和　birthday' => [['birthday_era'=>'昭和','birthday_year'=>1,'birthday_month'=>12,'birthday_day'=>25,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-33-4 昭和　birthday' => [['birthday_era'=>'昭和','birthday_year'=>64,'birthday_month'=>1,'birthday_day'=>7,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-33-5 昭和　birthday' => [['birthday_era'=>'昭和','birthday_year'=>64,'birthday_month'=>1,'birthday_day'=>8,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(昭和1年12月25日〜昭和64年1月7日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #大正
            't-34-1 大正　birthday' => [['birthday_era'=>'大正','birthday_year'=>2,'birthday_month'=>4,'birthday_day'=>31,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd' => '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-34-2 大正　birthday' => [['birthday_era'=>'大正','birthday_year'=>1,'birthday_month'=>7,'birthday_day'=>29,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(大正1年7月30日〜昭和15年12月24日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-34-3 大正　birthday' => [['birthday_era'=>'大正','birthday_year'=>1,'birthday_month'=>7,'birthday_day'=>30,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-34-4 大正　birthday' => [['birthday_era'=>'大正','birthday_year'=>15,'birthday_month'=>12,'birthday_day'=>24,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-34-5 大正　birthday' => [['birthday_era'=>'大正','birthday_year'=>15,'birthday_month'=>12,'birthday_day'=>25,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(大正1年7月30日〜昭和15年12月24日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            #明治
            't-35-1 明治　birthday' => [['birthday_era'=>'明治','birthday_year'=>1,'birthday_month'=>4,'birthday_day'=>31,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_ymd' => '実在する日付で入力してください',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-35-2 明治　birthday' => [['birthday_era'=>'明治','birthday_year'=>1,'birthday_month'=>1,'birthday_day'=>24,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(明治1年1月25日〜明治45年7月29日)',],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-35-3 明治　birthday' => [['birthday_era'=>'明治','birthday_year'=>1,'birthday_month'=>1,'birthday_day'=>25,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-35-4 明治　birthday' => [['birthday_era'=>'明治','birthday_year'=>45,'birthday_month'=>7,'birthday_day'=>29,],
                ['status'=>302,'redirect'=>'/member/register/checkview','error_items'=>[],'search_items'=>[],'not_search_items'=>[],],
            ],
            't-35-5 明治　birthday' => [['birthday_era'=>'明治','birthday_year'=>45,'birthday_month'=>7,'birthday_day'=>30,],
                ['status'=>302,'redirect'=>'/member/register/in','error_items'=>['wk_birthday_era_ymd' => '実在しない和暦です(明治1年1月25日〜明治45年7月29日)',],'search_items'=>[],'not_search_items'=>[],],
            ],


        ];
    }
}
