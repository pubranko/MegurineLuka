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
            'payment_select' => ['required',Rule::in("登録済みクレジットカード","個別指定クレジットカード")],
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
        $validator->sometimes('card_number',['required','max:19','regex:/^[0-9-]+$/u'],function($input){
            return $input->payment_select == "個別指定クレジットカード";
        });
        $validator->sometimes('card_month','required|digits:2',function($input){
            return $input->payment_select == "個別指定クレジットカード";
        });
        $validator->sometimes('card_year','required|digits:2',function($input){
            return $input->payment_select == "個別指定クレジットカード";
        });
        $validator->sometimes('card_name',['required','max:50','regex:/^[a-zA-Z0-9-,. \/]+$/u'],function($input){
            return $input->payment_select == "個別指定クレジットカード";
        });
        $validator->sometimes('card_security_code','required|digits_between:3,4',function($input){
            return $input->payment_select == "個別指定クレジットカード";
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'address_select.required'=> '選択が漏れています',
            'address_select.in'=> '不正な値です',
            'receiver_name.required' => '入力が漏れています',
            'receiver_name.max' => '６０文字まで入力可能です',
            'postal_code1.required' => '入力が漏れています',
            'postal_code1.digits' => '３桁で入力してください',
            'postal_code2.required' => '入力が漏れています',
            'postal_code2.digits' => '４桁で入力してください',
            'address1.required' => '入力が漏れています',
            'address2.required' => '入力が漏れています',
            'address3.required' => '入力が漏れています',
            'address4.required' => '入力が漏れています',
            'phone_select.required'=> '選択が漏れています',
            'phone_select.in'=> '不正な値です',
            'phone_number1.required' => '入力が漏れています',
            'phone_number1.digits_between' => '１〜１１桁で入力してください',
            'phone_number2.required' => '入力が漏れています',
            'phone_number2.digits_between' => '１〜４桁で入力してください',
            'phone_number3.required' => '入力が漏れています',
            'phone_number3.digits' => '４桁で入力してください',
        ];
    }
}
