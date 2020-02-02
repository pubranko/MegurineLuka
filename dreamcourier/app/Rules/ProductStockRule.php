<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductStockRule implements Rule
{
    /**
     * 外から受けるパラメータを定義(どこからでもアクセスできるように)
     */
    private $_product_code;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($product_code)
    {
        $this->_product_code = $product_code;
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
        $query = \App\ProductStockList::where('product_code',$this->_product_code)->first();
        if($query->product_stock_quantity > 0){
            return true;    #在庫有りの場合
        }else{
            return false;   #在庫無しの場合
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '商品の在庫がなくなったたため、決済処理がキャンセルされました。';
    }
}
