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
     * バリデーションの前処理（オーバーライド）。
     * 必要に応じて使用する予定。
     * @return array
     */
    public function validationData()
    {
        #配達日時のバリデーション用データをリクエストに追加
        if (isset($data['delivery_date']) && isset($data['delivery_time'])){
            $data = $this->all();
            $wk_delivery_time = explode("〜",$data['delivery_time'])[0];                    #例）「0:00〜2:00」の手前の時刻を取得
            $data['wk_delivery_datetime'] = $data['delivery_date']." ".$wk_delivery_time;   #配達希望日時を設定(yyyy-mm-dd hh:mm:ss形式)
            $data['wk_available_datetime'] = date("Y-m-d H:i:s",time() + (60*60*12));       #配達可能日時（現在時刻＋１２時間）を設定(yyyy-mm-dd hh:mm:ss形式)
            $this->merge($data);
        }
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
