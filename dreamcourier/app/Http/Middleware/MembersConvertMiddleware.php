<?php

namespace App\Http\Middleware;

use Closure;

class MembersConvertMiddleware
{
    /**
     * Membersテーブルに対する入力フォームで使用するルートミドルウェア。
     * １．全角しか認めていない項目へ半角が含まれていた場合、全角へ変換する。
     * ２．半角しか認めていない項目へ全角が含まれていた場合、半角へ変換する。
     * ３．必要な項目だけゼロサプレス
     * ４．個別の年月日を結合した年月日をリクエストに付与する。
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->all();
        #半角→全角に変換（RNKS：英字、数字、スペース、カタカナ）
        if (isset($data['last_name']))
            $data['last_name'] = mb_convert_kana($data['last_name'], 'RNKS');
        if (isset($data['first_name']))
            $data['first_name'] = mb_convert_kana($data['first_name'], 'RNKS');
        if (isset($data['last_name_kana']))
            $data['last_name_kana'] = mb_convert_kana($data['last_name_kana'], 'RNKS');
        if (isset($data['first_name_kana']))
            $data['first_name_kana'] = mb_convert_kana($data['first_name_kana'], 'RNKS');
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
        #年月日→ゼロサプレス
        if (isset($data['birthday_year']))
            $data['birthday_year'] = ltrim(mb_convert_kana($data['birthday_year'], 'n'),"0");
        if (isset($data['birthday_month']))
            $data['birthday_month'] = ltrim(mb_convert_kana($data['birthday_month'], 'n'),"0");
        if (isset($data['birthday_day']))
            $data['birthday_day'] = ltrim(mb_convert_kana($data['birthday_day'], 'n'),"0");
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

        #バリデート用に加工した生年月日のリクエストを追加する。
        #生年月日（西暦）：年月日による妥当性チェック用（例：”1925/1/9”）
        if (isset($data['birthday_era']) && isset($data['birthday_year']) && isset($data['birthday_month']) && isset($data['birthday_day'])){
            switch($data['birthday_era']){
                case "西暦":
                    $data['wk_birthday_ymd'] = $data['birthday_year']."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "令和":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+2018)."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "平成":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1988)."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "昭和":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1925)."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "大正":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1911)."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "明治":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1867)."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
            }
            #生年月日（和暦）：各元号ごとの範囲内チェック用（例：”010109”）
            if($data['birthday_era']!=="西暦"){
                $data['wk_birthday_era_ymd'] =  intval($data['birthday_year'].
                                                        str_pad($data['birthday_month'], 2, "0", STR_PAD_LEFT).
                                                        str_pad($data['birthday_day'], 2, "0", STR_PAD_LEFT));
            }
        }
        $request->merge($data);
        return $next($request);
    }
}
