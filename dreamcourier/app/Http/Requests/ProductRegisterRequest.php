<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SalesPeriodDuplicationRule;   #追加　独自ルール

class ProductRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'operator/product/register'){
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
        #再度バリデーションが必要な項目があるため、入力画面のリクエストをセッションより復元する。
        $data = $this->session()->get('product_register_in_request');
        #上述の追加項目をリクエストに反映させる
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
            'product_code' => [new SalesPeriodDuplicationRule(     #同一商品コードで販売期間が重複するレコードがある場合エラー
                                $this->product_code,
                                $this->wk_sales_period_from,
                                $this->wk_sales_period_to
                                )],
        ];
    }
    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     * @return array
     */
    public function withValidator ($validator){

    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     * @return array
     */
    public function messages(){
        return [
        ];
    }
}
