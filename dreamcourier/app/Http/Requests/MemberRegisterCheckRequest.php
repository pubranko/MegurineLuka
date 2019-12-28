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
            'email' => 'required|email|max:255|unique:members',
            'last_name' => 'required|max:30',
            'first_name' => 'required|max:30',
            'last_name_kana' => 'required|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|max:60',
            'first_name_kana' => 'required|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|max:60',
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
            'address4' => 'required',
            'phone_number1' => 'required|digits_between:1,11',
            'phone_number2' => 'required|digits_between:1,4',
            'phone_number3' => 'required|digits:4',
            'wk_birthday_ymd'=>'date',  #ミドルウェアでバリデート用に付与したリクエスト（和暦入力でも西暦に変換した状態）
        ];
    }

    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator ($validator){
        #格元号ごとの年月日の範囲チェック
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
            'email.required' => '入力が漏れています',
            'email.email' => 'メールアドレスの形式ではありません',
            'email.max' => 'メールアドレスの文字数が最大値を超えています',
            'email.unique' => '既に登録されているアドレスです',

            'last_name.required' => '入力が漏れています',
            'last_name.max' => '３０文字まで入力可能です',
            'first_name.required' => '入力が漏れています',
            'first_name.max' => '３０文字まで入力可能です',
            'last_name_kana.required' => '入力が漏れています',
            'last_name_kana.regex' => 'カタカナで入力してください',
            'last_name_kana.max' => '６０文字まで入力可能です',
            'first_name_kana.required' => '入力が漏れています',
            'first_name_kana.regex' => 'カタカナで入力してください',
            'first_name_kana.max' => '６０文字まで入力可能です',
            'birthday_era.required' => '入力が漏れています',
            'birthday_era.in' => '不正な値です',
            #'birthday_year' => 'required|digits:4',
            'birthday_month.required' => '入力が漏れています',
            'birthday_month.integer' => '数値で入力してください',
            'birthday_month.between' => '不正な値です',
            'birthday_day.required' => '入力が漏れています',
            'birthday_day.integer' => '数値で入力してください',
            'birthday_day.between' => '不正な値です',
            'sex.required.required' => '入力が漏れています',
            'sex.in' => '不正な値です',
            'postal_code1.required' => '入力が漏れています',
            'postal_code1.digits' => '３桁で入力してください',
            'postal_code2.required' => '入力が漏れています',
            'postal_code2.digits' => '４桁で入力してください',
            'address1.required' => '入力が漏れています',
            'address2.required' => '入力が漏れています',
            'address3.required' => '入力が漏れています',
            'address4.required' => '入力が漏れています',
            'phone_number1.required' => '入力が漏れています',
            'phone_number1.digits_between' => '１〜１１桁で入力してください',
            'phone_number2.required' => '入力が漏れています',
            'phone_number2.digits_between' => '１〜４桁で入力してください',
            'phone_number3.required' => '入力が漏れています',
            'phone_number3.digits' => '４桁で入力してください',
            'wk_birthday_ymd.date'=> '実在する日付で入力してください',
            'wk_birthday_era_ymd.between' => '実在する日付で入力してください'
        ];
    }
}
