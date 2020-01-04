<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator; #追加
use App\Http\Requests\MemberRegisterRequest;   #追加
use Illuminate\Foundation\Testing\DatabaseMigrations; #追加
use App\Member;                                                #追加
/**
 * 新規会員登録（確認）で入力された値のバリデートのテスト
 */
class MemberRegisterTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * カスタムリクエストのバリデーションテスト（正常系）
     *
     * @dataProvider dataproviderNomal
     */
    public function testNomal($query, $expect)
    {
        $this->assertTrue(true);
        $request = new MemberRegisterRequest();
        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();
        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($query, $rules);
        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataproviderNomal()
    {
        return [
            '正常' => [["email"=>"testmail@outlook.com","password"=>"aaaabbbb","password_confirmation"=>"aaaabbbb"],true],
        ];
    }

    /**
     * カスタムリクエストのバリデーションテスト（エラー系）
     *
     * @dataProvider dataproviderError
     */
    public function testError($query, $expect)
    {
        $this->assertTrue(true);
        $request = new MemberRegisterRequest();
        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();
        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($query, $rules);
        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
        $this->assertEquals($expect, $result);
    }

    public function dataproviderError()
    {
        return [
            #'正常' => [["email"=>"testmail@outlook.com","password"=>"aaaabbbb","password_confirmation"=>"aaaabbbb"],true],
            'エラー(password) required              ' => [["email"=>"testmail@outlook.com","password"=>"","password_confirmation"=>"aaaabbbb"],false],
            'エラー(password) min                   ' => [["email"=>"testmail@outlook.com","password"=>"1234567","password_confirmation"=>"1234567"],false],
            'エラー(password) same                  ' => [["email"=>"testmail@outlook.com","password"=>"AAAAdddd","password_confirmation"=>"12345678"],false],
            'エラー(password_confirmation) required ' => [["email"=>"testmail@outlook.com","password"=>"aaaabbbb","password_confirmation"=>""],false],
        ];
    }

    /**
     * カスタムリクエストのバリデーションテスト（E-mailの重複チェック）
     *
     * @dataProvider dataproviderEmailUnique
     */
    public function testEmailUnique($query, $expect)
    {
        $this->assertTrue(true);
        factory(Member::class,1)->states('EmailUnique') ->create(); //Memberモデルクラスを、factory(※~/database/factores/MemberFactory)に元々定義されていたメソッドに渡してインスタンスにしているっぽい。

        $request = new MemberRegisterRequest();
        //フォームリクエストで定義したルールを取得
        $rules = $request->rules();
        //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
        $validator = Validator::make($query, $rules);
        //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataproviderEmailUnique()
    {
        return [
            'email 重複エラー' => [["email"=>"unique@ex.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
        ];
    }


}
