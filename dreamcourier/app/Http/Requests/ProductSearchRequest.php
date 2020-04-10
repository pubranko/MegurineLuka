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
            'product_stock_quantity_from' => 'integer',                                     #商品在庫数（以上）
            'product_stock_quantity_to' => 'integer',                                       #商品在庫数（以下）
            'sales_period_date_from' => 'date|required_with:sales_period_time_from',        #販売期間（FROM日付）
            'sales_period_time_from' =>['regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],     #販売期間（FROM時間）
            'sales_period_date_to' => 'date|required_with:sales_period_time_to',            #販売期間（TO日付）
            'sales_period_time_to' => ['regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],      #販売期間（TO時間）
            'status.*' => [Rule::in("正式","仮登録","仮変更")],                             #ステータス
            'selling_discontinued_classification.*' => [Rule::in("販売可","販売中止")],     #販売状況ステータス
            'product_list_details' => 'required|integer',                                   #表示明細数
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
        $validator->sometimes('product_stock_quantity_to','gte:product_stock_quantity_from',function($input){
            return isset($input->product_stock_quantity_from) && isset($input->product_stock_quantity_to);
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
            'sales_period_date_from.date' => '販売期間FROM：日付形式が不正です',
            'sales_period_time_from.regex' => '販売期間FROM：時間形式が不正な値です',
            'sales_period_date_from.required_with' => '販売期間FROM：入力が漏れています',
            'sales_period_date_to.date' => '販売期間TO：日付形式が不正です',
            'sales_period_time_to.required_with' => '販売期間TO：入力が漏れています',
            'sales_period_time_to.regex' => '販売期間TO：時間形式が不正な値です',
            'wk_sales_period_to.after' => '販売期間の範囲が不正です',
            'product_stock_quantity_from.integer' => '商品在庫数（以上）：数値で入力してください',
            'product_stock_quantity_to.integer' => '商品在庫数（以下）：数値で入力してください',
            'product_stock_quantity_to.gte' => '商品在庫数の範囲が不正です',
            'product_list_details.required' => '表示明細数：入力が漏れています',
            'product_list_details.integer' => '表示明細数：数値で入力してください',
        ];
    }
}
