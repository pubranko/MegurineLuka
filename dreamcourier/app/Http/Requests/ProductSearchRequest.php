<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class ProductSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'operator/product/search'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * バリデーションの前処理（オーバーライド）。
     * 初画面表示（メニュー画面から検索画面を表示）する際、
     * バリデーションエラーを回避するため、以下のクエリーをリクエストに追加する。
     * @return array
     */
    public function validationData()
    {
        $data = $this->all();
        if(!isset($data['product_list_details'])){  #表示明細数が存在しなかった場合
            $data['product_list_details'] = 20;     #表示明細数を追加（標準で２０件とする）
        }
        $this->merge($data);                #上述のコンバート内容をリクエストに反映させる

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
            #'product_code' => 'regex:/^[a-zA-Z0-9]*-[0-9]{3}$/u',                           #商品コード
            'sales_period_date_from' => 'date|required_with:sales_period_time_from',        #販売期間（FROM日付）
            'sales_period_time_from' =>['regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],       #販売期間（FROM時間）
            'sales_period_date_to' => 'date|required_with:sales_period_time_to',          #販売期間（TO日付）
            'sales_period_time_to' => ['regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],        #販売期間（TO時間）
            'product_price' => 'integer',                                                   #商品価格
            'product_list_details' => 'required|integer',                                   #表示明細数
            #'status[]' => [Rule::in("正式","仮登録","仮変更")],
            'status[]' => 'array',
            'product_stock_quantity_from' => 'integer',
            'product_stock_quantity_to' => 'integer',
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
        $validator->sometimes('wk_sales_period_to','after:wk_sales_period_from',function($input){
            return isset($input->wk_sales_period_to) && isset($input->wk_sales_period_from);
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            #'product_code.regex' => '入力パターンが不正です　※例:Syouhin001-003,S10-123',
            'sales_period_date_from.date' => '日付形式が不正です',
            'sales_period_time_from.regex' => '時間形式が不正な値です',
            'sales_period_date_to.required_with' => '入力が漏れています',
            'sales_period_date_to.date' => '日付形式が不正です',
            'sales_period_time_to.required_with' => '入力が漏れています',
            'sales_period_time_to.regex' => '時間形式が不正な値です',
            'wk_sales_period_to.after' => '販売期間の範囲が不正です',
            'product_price.integer' => '数値で入力してください',
            'product_stock_quantity_from.integer' => '数値で入力してください',
            'product_stock_quantity_to.integer' => '数値で入力してください',
        ];
    }
}
