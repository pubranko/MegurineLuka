<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductMasterIdCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*if($this->path() == 'member/show'){
            return true;
        }elseif($this->path() == 'show'){
            return true;
        }elseif($this->path() == 'member/cart_add'){
            return true;
        }else{
            return false;
        }*/
        return true;
    }

    /**
     * バリデーションの前処理（オーバーライド）。
     * @return array
     */
    public function validationData()
    {
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
            'id' => 'required|integer|exists:product_masters',
        ];
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'id.required' => 'IDがありません',
            'id.integer' => 'IDが数値以外となっています',
            'id.exists' => '存在しないIDです',
        ];
    }
}
