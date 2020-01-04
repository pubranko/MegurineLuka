<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ProductMasters;

#class ReservationRule implements Rule
class SalesPeriodDuplicationRule implements Rule

{
    /**
     * 外から受けるパラメータを定義(どこからでもアクセスできるように)
     */
    private $_product_code,
            $_sales_period_from,
            $_sales_period_to;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($product_code,$sales_period_from,$sales_period_to)
    {
        //
        $this->_product_code = $product_code;
        $this->_sales_period_from = $sales_period_from;
        $this->_sales_period_to = $sales_period_to;
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
        //サンプル
        return \App\ProductMasters::where('product_code', $this->_product_code)             #同一の商品コードのレコードに対して、
        ->SalesPeriodDuplicationCheck($this->_sales_period_from, $this->_sales_period_to)   #モデルのスコープで販売期間の重複があるレコードの有無を調査。
        ->doesntExist();                                                                    #該当するものがなければ、trueを返す。
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '同一商品コードで、販売期間が重複するレコードがあります';
    }
}
