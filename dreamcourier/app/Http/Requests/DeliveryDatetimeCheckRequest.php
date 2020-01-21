<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class DeliveryDatetimeCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'member/delivery_datetime'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_date'=>'required|date',
            'delivery_time'=>['required',
                                Rule::in('0:00〜2:00','2:00〜4:00','4:00〜6:00','6:00〜8:00',
                                        '8:00〜10:00','10:00〜12:00','12:00〜14:00','14:00〜16:00',
                                        '16:00〜18:00','18:00〜20:00','20:00〜22:00','22:00〜24:00',)],   #配達時間
            'wk_delivery_datetime'=>'after:wk_available_datetime',
        ];
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'delivery_date.required'=>'入力が漏れています',
            'delivery_date.date'=>'日付形式が不正です',
            'delivery_time.required'=>'入力が漏れています',
            'delivery_time.in'=>'時間帯指定が不正です',
            'wk_delivery_datetime.after'=>'配達可能日時は、現時刻より１２時間以降となります',
        ];
    }
}
