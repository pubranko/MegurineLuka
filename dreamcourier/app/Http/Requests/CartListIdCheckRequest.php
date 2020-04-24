<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartListIdCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cartlist_id' => 'required|integer|exists:product_cart_lists,id',
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
