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

        #例外：パスワードに関しては空白の除去の対象外
        unset($input['password']);
        unset($input['password_confirmation']);

        $convert = [];
        foreach($input as $key => $val)
        {
            // 入力フォームの前後のスペース(全角・半角)を除去する
            $convert[$key] = preg_replace('/(^\s+)|(\s+$)/u', '', $val);
            #$trimmed[$key] = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $pString);   #タブ等も消す場合、こんな方法もある。
        }

        $request->merge($convert);  #空白除去後の値でリクエストを上書き
        return $next($request);
    }
}
