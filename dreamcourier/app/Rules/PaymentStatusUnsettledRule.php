<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PaymentStatusUnsettledRule implements Rule
{
    /**
     * 外から受けるパラメータを定義(どこからでもアクセスできるように)
     */
    private $_cartlist_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($cartlist_id)
    {
        $this->_cartlist_id = $cartlist_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $query = \App\ProductCartList::find($this->_cartlist_id);
        if($query->payment_status == '未決済'){
            return true;
        }else{
            return false;
        }

        #return \App\ProductCartList::find($this->_cartlist_id)
        #->SalesPeriodDuplicationCheck($this->_sales_period_from, $this->_sales_period_to)   #モデルのスコープで販売期間の重複があるレコードの有無を調査。
        #->doesntExist();                                                                    #該当するものがなければ、trueを返す。

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '購入手続き中であったカートの商品が、既に決済済み、またはキャンセルされていたため決済処理を中止しました。';
    }
}
