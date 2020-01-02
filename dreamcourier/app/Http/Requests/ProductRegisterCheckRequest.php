<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRegisterCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'operator/register'){
            if($this->all()['check']){              #クエリーに"check"があれば、バリデーションを実行
                return true;
            }
        }
        return false;
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
            //
            #'email' => 'required|email|max:255|unique:members',
            #'last_name' => 'required|max:30',

            'product_code' => 'required|regex:/^[a-zA-Z0-9]*-[0-9]+$/u',   #商品コード
            'sales_period_from' => 'required|date',                 #販売期間（FROM）
            #'sales_period_to' => 'date',                           #販売期間（TO）
            'product_name' => 'required|max:200',                           #商品名
            'product_description' => 'required|max:1500',                    #商品説明
            'product_price' => 'required|integer',                  #商品価格
            'product_image' => 'required|image|dimensions:ratio=1/1',       #商品画像
            'product_thumbnail' => 'required|image|dimensions:ratio=1/1',   #商品サムネイル画像
            'product_search_keyword' => 'required',                 #商品検索キーワード
            'product_tag' => 'required',                            #商品タグ
            'product_stock_quantity' => 'required|integer',         #商品在庫数

            #こんな記述方法もある
            # use Illuminate\Validation\Rule;
            #'email' => ['required', Rule::exists('staff')->where(function ($query) { $query->where('account_id', 1); }),],
        ];
    }

    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator ($validator){
        #販売期間（TO）
        $validator->sometimes('sales_period_to','date|after:sales_period_from',function($input){
            return isset($input->sales_period_to);
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            #'email.required' => '入力が漏れています',
            #'wk_birthday_era_ymd.between' => '実在する日付で入力してください'
        ];
    }
}
