<?php

namespace App\Http\Middleware;

use Closure;

class RequestConvertMiddleware
{
    /**
     * 全画面で使用するグローバルミドルウェア
     * 入力の前後の空白（全角・半角）を除去する。
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $input = $request->all();

        $convert = [];
        foreach($input as $key => $val)
        {
            // 入力フォームの前後のスペース(全角・半角)を除去する
            $convert[$key] = preg_replace('/(^\s+)|(\s+$)/u', '', $val);
            #$trimmed[$key] = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $pString);
        }

        $request->merge($convert);
        return $next($request);
    }
}
