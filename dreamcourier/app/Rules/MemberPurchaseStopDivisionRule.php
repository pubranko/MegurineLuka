<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MemberPurchaseStopDivisionRule implements Rule
{
    /**
     * 外から受けるパラメータを定義(どこからでもアクセスできるように)
     */
    private $_member_code;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($member_code)
    {
        $this->_member_code = $member_code;
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
        $query = \App\Member::query();
        $member = $query->where('member_code',$this->_member_code)->where('status','正式')->first();
        if($member->purchase_stop_division =='購入停止'){
            return false;   #購入停止の場合
        }else{
            return true;    #購入可の場合
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '現在お客様のご購入は、諸事情により停止させて頂いております。';
    }
}
