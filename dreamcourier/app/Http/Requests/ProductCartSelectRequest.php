<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCartSelectRequest extends FormRequest
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
            'cartlist_id' => ['required','integer','exists:product_cart_lists,id'],
        ];
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'cartlist_id.required' => 'IDがありません',
            'cartlist_id.integer' => 'IDが数値以外となっています',
            'cartlist_id.exists' => '存在しないIDです',
        ];
    }
}
