<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class MemberRegisterCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #echo "フォームリクエスト！";
        #return true;
        if($this->path() == 'member/registercheck'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * バリデーションの前処理（オーバーライド）。
     * 必要に応じて使用する予定。
     * @return array
     */
    public function validationData()
    {
        #例
        #$data = $this->all();
        #if (isset($data['last_name']))
        #    $data['last_name'] = mb_convert_kana($data['last_name'], 'RNKS');
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            #項目関連チェック、テーブル関連チェックもあとで検討

            'email' => 'required|email|max:255|unique:members',
            'last_name' => 'required|string|max:30',
            'first_name' => 'required|string|max:30',
            'last_name_kana' => 'required|string|max:60',
            'first_name_kana' => 'required|string|max:60',
            #生年月日のバリデーションもあとで検討 'birthday' => 'required',
            'birthday_era' => ['required',Rule::in("西暦","令和","平成","昭和","大正","明治")],
            #'birthday_year' => 'required|digits:4',
            'birthday_month' => 'required|integer|between:1,12',    #integerがないと桁数1-12でチェックしてしまった。
            'birthday_day' => 'required|integer|between:1,31',
            'sex' => ['required',Rule::in("男性","女性")],
            'postal_code1' => 'required|digits:3',
            'postal_code2' => 'required|digits:4',
            'address1' => 'required',
            'address2' => 'required',
            'address3' => 'required',
            'address4' => 'required|string',
            'phone_number1' => 'required|digits_between:1,11',
            'phone_number2' => 'required|digits_between:1,4',
            'phone_number3' => 'required|digits:4',
            'wk_birthday_ymd'=>'date',
        ];
    }

    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator ($validator){

        #$validator->sometimes('wk_birthday_ymd','date',function($input){
        #    return $input->birthday_era == "西暦";
        #});
        $validator->sometimes('wk_birthday_era_ymd','integer|min:10501',function($input){
            return $input->birthday_era == "令和";
        });
        $validator->sometimes('wk_birthday_era_ymd','integer|between:10108,310430',function($input){
            return $input->birthday_era == "平成";
        });
        $validator->sometimes('wk_birthday_era_ymd','integer|between:11225,640107',function($input){
            return $input->birthday_era == "昭和";
        });
        $validator->sometimes('wk_birthday_era_ymd','integer|between:10730,151224',function($input){
            return $input->birthday_era == "大正";
        });
        $validator->sometimes('wk_birthday_era_ymd','integer|between:10125,450729',function($input){
            return $input->birthday_era == "明治";
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'email.required' => 'メールアドレスは必ず指定してください。',
            'email.email' => 'メールアドレスの形式ではありません。',
            'email.max' => 'メールアドレスの文字数が最大値を超えています。',
            'email.unique' => '既に登録されているアドレスです。',
            #まだまだ足りない。あとで追加
            'wk_birthday_era_ymd.between' => 'その元号で指定できる範囲の日付ではありません。'
        ];
    }
}
