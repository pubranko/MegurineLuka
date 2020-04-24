<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class DeliveryPaymentCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'member/delivery_payment'){
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
        $data = $this->all();

        #全角→半角に変換 (n:数字,a:英数字,s:空白)
        if (isset($data['card_number']))
            $data['card_number'] = mb_convert_kana($data['card_number'], 'a');
        if (isset($data['card_name']))
            $data['card_name'] = mb_convert_kana($data['card_name'], 'as');
        if (isset($data['card_security_code']))
            $data['card_security_code'] = mb_convert_kana($data['card_security_code'], 'n');

        $this->merge($data);
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
            'payment_select' => ['required',Rule::in('登録済みクレジットカード','個別指定クレジットカード')],
        ];
    }
    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator ($validator){
        #個別指定クレジットカードを指定した場合、以下のチェックを行う
        $validator->sometimes('card_number',['required','max:19','regex:/^[0-9-]+$/u'],function($input){
            return $input->payment_select == '個別指定クレジットカード';
        });
        $validator->sometimes('card_month',['required','digits:2','regex:/^([0][1-9]|[1][0-2])+$/u'],function($input){
            return $input->payment_select == '個別指定クレジットカード';
        });
        $validator->sometimes('card_year','required|digits:2',function($input){
            return $input->payment_select == '個別指定クレジットカード';
        });
        $validator->sometimes('card_name',['required','max:50','regex:/^[a-zA-Z0-9-,. \/]+$/u'],function($input){
            return $input->payment_select == '個別指定クレジットカード';
        });
        $validator->sometimes('card_security_code','required|digits_between:3,4',function($input){
            return $input->payment_select == '個別指定クレジットカード';
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'payment_select.required'=> '選択が漏れています',
            'payment_select.in'=> '不正な値です',
            'card_number.required'=>'入力が漏れています',
            'card_number.max'=>'１９文字まで入力可能です',
            'card_number.regex'=>'数字とハイフン以外が含まれています',
            'card_month.required'=>'入力が漏れています',
            'card_month.digits'=>'２桁で入力してください',
            'card_month.regex'=>'０１〜１２の間で入力してください',
            'card_year.required'=>'入力が漏れています',
            'card_year.digits'=>'２桁で入力してください',
            'card_name.required'=>'入力が漏れています',
            'card_name.max'=>'５０文字まで入力可能です',
            'card_name.regex'=>'英字(a-z,A-Z)、数字(0-9)、半角スペース( )、カンマ(,)、ピリオド(.)、ハイフン(-)、スラッシュ(/)で入力してください',
            'card_security_code.required'=>'入力が漏れています',
            'card_security_code.digits_between'=>'３〜４桁で入力してください',
        ];
    }
}
