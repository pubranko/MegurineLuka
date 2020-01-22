<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PaymentStatusUnsettledRule;   #追加
use App\Rules\ProductStockRule;   #追加
use App\Rules\MemberPurchaseStopDivisionRule;    #追加
use App\Rules\SellingDiscontinuedRule;   #追加
use Auth;   #追加

class DeliveryRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'member/delivery_register'){
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
        #購入手続き決済前最終確認（バリデーション）用に、セッションより値を復元する。
        #決済したいカートリストのID
        $session['cartLists'] = $this->session()->get('cartLists');
        $data['cartlist_id'] = $session['cartLists']['cartlist_id'];
        #購入した商品コード
        $session['items'] = $this->session()->get('items');
        $data['product_code'] = $session['items']['wk_product']['product_code'];
        #ログイン中のメンバーコード
        $data['member_code'] = Auth::user()->member_code;

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
            'cartlist_id'=>[new PaymentStatusUnsettledRule($this->cartlist_id),],       #既に決済済み・キャンセルされていた場合エラー
            'product_code'=>[new SellingDiscontinuedRule($this->product_code),          #商品の販売が中止されていた場合エラー
                                new ProductStockRule($this->product_code),],            #商品の在庫がなかったらエラー
            'member_code'=>[new MemberPurchaseStopDivisionRule($this->member_code),],   #会員への販売が停止されていた場合エラー
        ];
    }
}
