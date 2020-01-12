<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations; #追加
use Illuminate\Support\Facades\Validator; #追加
use App\Http\Requests\MemberRegisterCheckRequest;   #追加
use App\Member;                                                #追加
/**
 * 新規会員登録（入力）で入力された値のバリデートのテスト
 */
class MemberRegisterCheckTest extends TestCase
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
        $request = new MemberRegisterCheckRequest();
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
            '正常' => [["email"=>"testmail@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(email)3 max255  ' => [["email"=>"testmail@outlook.comaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaa",
                                       "last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(last_name)2 max30   ' => [["email"=>"testmail1@outlook.com","last_name"=>"あああああいいいいいうううううえええええおおおおおかかかかか","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(first_name)2 max30   ' => [["email"=>"testmail1@outlook.com","last_name"=>"あああああいいいいいうううううえええええおおおおおかかかかか","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

            '正常(last_kana_name)3 max60   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(first_kana_name)3 max60   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"令和","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"平成","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"昭和","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"大正","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"明治","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

            '正常(birthday_month)3 between 1-12' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"1","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_month)4 between 1-12' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"12","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_day)3 between 1-31' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"1","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(birthday_day)4 between 1-31' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"31","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

            '正常(sex)1 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(sex)1 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"女性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

            '正常(phone_number1)2 max11 ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"11122233344","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],
            '正常(phone_number2)2 max4  ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"1234","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],true],

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
        $request = new MemberRegisterCheckRequest();
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
            #'正常パターン’ => [["email"=>"testmail@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(email)1 required' => [["email"=>"","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(email)2 email   ' => [["email"=>"mikuras2@","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(email)3 max255  ' => [["email"=>"testmail@outlook.comaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaAaaaaaaaaA",
                                            "last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(last_name)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(last_name)2 max30   ' => [["email"=>"testmail1@outlook.com","last_name"=>"あああああいいいいいうううううえええええおおおおおかかかかかき","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(first_name)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(first_name)2 max30   ' => [["email"=>"testmail1@outlook.com","last_name"=>"あああああいいいいいうううううえええええおおおおおかかかかかき","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(last_kana_name)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(last_kana_name)2 regix   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"あいう","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(last_kana_name)3 max60   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(first_kana_name)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(first_kana_name)2 regix   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"あいう","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(first_kana_name)3 max60   ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"アアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウアアアアアイイイイイウウウウウカ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(birthday_era)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_era)2 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"元禄","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(birthday_year)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_year)2 integer ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"abcd","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_month)1 required    ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_month)2 integer     ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"a","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_month)3 between 1-12' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"0","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_month)4 between 1-12' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"13","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_day)1 required    ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_day)2 integer     ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"b","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_day)3 between 1-31' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"0","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(birthday_day)4 between 1-31' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"32","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(sex)1 in      ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男女","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(postal_code1)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(postal_code1)2 digits 3' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"1234","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(postal_code2)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(postal_code2)2 digits 4' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"123","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(address1)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(address2)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(address3)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(address4)1 required' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],

            'エラー(phone_number1)1 required  ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(phone_number1)2 max 11    ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"111222333444","phone_number2"=>"5555","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(phone_number2)1 required  ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(phone_number2)2 max 4     ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"12345","phone_number3"=>"3333","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(phone_number3)1 required  ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"","wk_birthday_ymd"=>"1999/2/3"],false],
            'エラー(phone_number3)2 digits 4  ' => [["email"=>"testmail1@outlook.com","last_name"=>"田中","first_name"=>"太郎","last_name_kana"=>"タナカ","first_name_kana"=>"タロウ","birthday_era"=>"西暦","birthday_year"=>"1977","birthday_month"=>"4","birthday_day"=>"11","sex"=>"男性","postal_code1"=>"123","postal_code2"=>"4567","address1"=>"埼玉県","address2"=>"草加市","address3"=>"西町","address4"=>"７６５−５","address5"=>"セフィラ西","address6"=>"２０３","phone_number1"=>"090","phone_number2"=>"5555","phone_number3"=>"123","wk_birthday_ymd"=>"1999/2/3"],false],

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

        $request = new MemberRegisterCheckRequest();
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
