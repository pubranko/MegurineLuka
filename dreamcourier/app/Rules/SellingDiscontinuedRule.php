<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SellingDiscontinuedRule implements Rule
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
        $query = \App\ProductMaster::query();
        $query->where('product_code',$this->_product_code);
        $query->where('status','正式');
        $query->SalesPeriodDuplicationCheck($this->_product_code,date('Y-m-d H:i:s'),'2100-12-31 23:59:59');
        $product = $query->first();
        if($product->selling_discontinued_classification =='販売中止'){
            return false;   #販売中止の場合
        }else{
            return true;    #販売可の場合
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'この商品は、現在販売を中止させて頂いております。';
    }
}
