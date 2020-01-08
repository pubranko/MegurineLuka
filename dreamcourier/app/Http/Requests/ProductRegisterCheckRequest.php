<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SalesPeriodDuplicationRule;   #追加

class ProductRegisterCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'operator/product/register/check'){
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
            'product_code' => ['required','regex:/^[a-zA-Z0-9]*-[0-9]{3}$/u',   #商品コード
                            new SalesPeriodDuplicationRule(     #同一商品コードで販売期間が重複するレコードがある場合エラー
                                $this->product_code,
                                $this->wk_sales_period_from,
                                $this->wk_sales_period_to
                                )],
            'sales_period_date_from' => 'required|date|after:2019-01-01',                                              #販売期間（FROM日付）
            'sales_period_time_from' => ['required','regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],   #販売期間（FROM時間）
            'sales_period_date_to' => 'required_with:sales_period_time_to|date',                      #販売期間（TO日付）
            'sales_period_time_to' => ['required_with:sales_period_daProductSearchte_to',                          #販売期間（TO時間）
                                       'regex:/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/u'],
            'product_name' => 'required|max:200',                           #商品名
            'product_description' => 'required|max:1500',                   #商品説明
            'product_price' => 'required|integer',                          #商品価格
            'product_image' => 'required|image|dimensions:ratio=1/1',       #商品画像
            'product_thumbnail' => 'required|image|dimensions:ratio=1/1',   #商品サムネイル画像
            'product_search_keyword' => 'required',                         #商品検索キーワード
            'product_tag' => 'required',                                    #商品タグ
            'product_stock_quantity' => 'required|integer',                 #商品在庫数

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
        $validator->sometimes('wk_sales_period_to','after:wk_sales_period_from',function($input){
            return isset($input->wk_sales_period_to);
        });
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'product_code.required' => '入力が漏れています',
            'product_code.regex' => '入力パターンが不正です　※例:Syouhin001-003,S10-123',
            'sales_period_date_from.required' => '入力が漏れています',
            'sales_period_date_from.date' => '日付形式が不正です',
            'sales_period_date_from.after' => '2019年より以前の日付は入力不可です',
            'sales_period_time_from.required' => '入力が漏れています',
            'sales_period_time_from.regex' => '時間形式が不正な値です',
            'sales_period_date_to.required_with' => '入力が漏れています',
            'sales_period_date_to.date' => '日付形式が不正です',
            'sales_period_time_to.required_with' => '入力が漏れています',
            'sales_period_time_to.regex' => '時間形式が不正な値です',
            'wk_sales_period_to.after' => '販売期間の範囲が不正です',
            'product_name.required' => '入力が漏れています',
            'product_name.max' => '２００文字まで入力可能です',
            'product_description.required' => '入力が漏れています',
            'product_description.max' => '１５００文字まで入力可能です',
            'product_price.required' => '入力が漏れています',
            'product_price.integer' => '数値で入力してください',
            'product_image.required' => '入力が漏れています',
            'product_image.image' => '画像ファイル（jpg、png、bmp、gif、svg）を指定してください',
            'product_image.dimensions' => '画像の縦横比は１：１のみ登録可能です',
            'product_thumbnail.required' => '入力が漏れています',
            'product_thumbnail.image' => '画像ファイル（jpg、png、bmp、gif、svg）を指定してください',
            'product_thumbnail.dimensions' => '画像の縦横比は１：１のみ登録可能です',
            'product_search_keyword.required' => '入力が漏れています',
            'product_tag.required' => '入力が漏れています',
            'product_stock_quantity.required' => '入力が漏れています',
            'product_stock_quantity.integer' => '数値で入力してください',
        ];
    }
}
