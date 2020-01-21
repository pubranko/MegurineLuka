<?php

namespace App\Http\Middleware;

use Closure;

class DeliveryProcedureMiddleware
{
    /**
     * 配送手続き関連画面の入力フォームで使用するルートミドルウェア。
     * １．全角しか認めていない項目へ半角が含まれていた場合、全角へ変換する。
     * ２．半角しか認めていない項目へ全角が含まれていた場合、半角へ変換する。
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->all();

        #半角→全角に変換（RNKS：英字、数字、スペース、カタカナ）
        if (isset($data['receiver_name']))
            $data['receiver_name'] = mb_convert_kana($data['receiver_name'], 'RNKS');
        if (isset($data['address1']))
            $data['address1'] = mb_convert_kana($data['address1'], 'RNKS');
        if (isset($data['address2']))
            $data['address2'] = mb_convert_kana($data['address2'], 'RNKS');
        if (isset($data['address3']))
            $data['address3'] = mb_convert_kana($data['address3'], 'RNKS');
        if (isset($data['address4']))
            $data['address4'] = mb_convert_kana($data['address4'], 'RNKS');
        if (isset($data['address5']))
            $data['address5'] = mb_convert_kana($data['address5'], 'RNKS');
        if (isset($data['address6']))
            $data['address6'] = mb_convert_kana($data['address6'], 'RNKS');
        #全角→半角に変換 (n:数字)
        if (isset($data['postal_code1']))
            $data['postal_code1'] = mb_convert_kana($data['postal_code1'], 'n');
        if (isset($data['postal_code2']))
            $data['postal_code2'] = mb_convert_kana($data['postal_code2'], 'n');
        if (isset($data['phone_number1']))
            $data['phone_number1'] = mb_convert_kana($data['phone_number1'], 'n');
        if (isset($data['phone_number2']))
            $data['phone_number2'] = mb_convert_kana($data['phone_number2'], 'n');
        if (isset($data['phone_number3']))
            $data['phone_number3'] = mb_convert_kana($data['phone_number3'], 'n');

        #配達日時のバリデーション用データをリクエストに追加
        if (isset($data['delivery_date']) && isset($data['delivery_time'])){
            $wk_delivery_time = explode("〜",$data['delivery_time'])[0];    #「0:00〜2:00」の手前の時刻を取得
            $wk_delivery_datetime = $data['delivery_date']." ".$wk_delivery_time;   #
            $wk_available_datetime = date("Y-m-d H:i:s",time() + (60*60*12));    #現在時刻＋１２時間後の「yyyy-mm-dd hh:mm:ss」形式

            $data['wk_delivery_datetime'] = $wk_delivery_datetime;      #配達希望日時を設定
            $data['wk_available_datetime'] = $wk_available_datetime;    #配達可能日時（現在時刻＋１２時間）を設定

            #dd($data);
        }

        $request->merge($data);
        return $next($request);
    }
}
