<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class DeliveryAddressCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'member/delivery_address'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_select' => ['required',Rule::in("登録済み住所","個別指定住所")],
            'phone_select' => ['required',Rule::in("登録済み電話番号","個別指定電話番号")],
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
        $validator->sometimes('receiver_name','required|max:60',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('postal_code1','required|digits:3',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('postal_code2','required|digits:4',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('address1','required',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('address2','required',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('address3','required',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('address4','required',function($input){
            return $input->address_select == "個別指定住所";
        });
        $validator->sometimes('phone_number1','required|max:11',function($input){
            return $input->phone_select == "個別指定電話番号";
        });
        $validator->sometimes('phone_number2','required|max:4',function($input){
            return $input->phone_select == "個別指定電話番号";
        });
        $validator->sometimes('phone_number3','required|digits:4',function($input){
            return $input->phone_select == "個別指定電話番号";
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
