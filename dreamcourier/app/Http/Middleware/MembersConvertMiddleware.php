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

        $request->merge($data);
        return $next($request);
    }
}
