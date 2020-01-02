<?php

namespace App\Http\Middleware;

use Closure;

class ProductRegisterConvertMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        #全角→半角に変換 (rn:r英字、n数字)
        if (isset($data['product_code']))
            $data['product_code'] = mb_convert_kana($data['product_code'], 'rn');
        #全角→半角に変換 (n:数字)
        if (isset($data['product_price']))
            $data['product_price'] = mb_convert_kana($data['product_price'], 'n');
        if (isset($data['product_stock_quantity']))
            $data['product_stock_quantity'] = mb_convert_kana($data['product_stock_quantity'], 'n');

        $request->merge($data);
        return $next($request);
    }
}
