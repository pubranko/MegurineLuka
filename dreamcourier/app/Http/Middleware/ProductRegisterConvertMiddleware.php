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
        $data = $request->except(['product_image', 'product_thumbnail']);

        #全角→半角に変換 (rn:r英字、n数字)
        if (isset($data['product_code']))
            $data['product_code'] = mb_convert_kana($data['product_code'], 'rn');
        #全角→半角に変換 (n:数字)
        if (isset($data['product_price']))
            $data['product_price'] = mb_convert_kana($data['product_price'], 'n');
        if (isset($data['product_stock_quantity']))
            $data['product_stock_quantity'] = mb_convert_kana($data['product_stock_quantity'], 'n');

        #
        #販売期間の日付と時間より、日時オブジェクトを追加
        #dd($data['sales_period_date_from']." ".$data['sales_period_time_from']);
        #"2020-01-03 10:00"
        if(isset($data['sales_period_date_from']) && isset($data['sales_period_time_from'])){
            #dd($data['sales_period_date_from']." ".$data['sales_period_time_from']);
            #$data['wk_sales_period_from'] = strtotime($data['sales_period_date_from']." ".$data['sales_period_time_from']);
            $data['wk_sales_period_from'] = $data['sales_period_date_from']." ".$data['sales_period_time_from'];    #"yyyy-mm-dd hh:mm"
            #dd($data['wk_sales_period_from']);
        }
        if(isset($data['sales_period_date_to']) && isset($data['sales_period_time_to'])){
            #$data['wk_sales_period_to'] = strtotime($data['sales_period_date_to']." ".$data['sales_period_time_to']);
            $data['wk_sales_period_to'] = $data['sales_period_date_to']." ".$data['sales_period_time_to'];
            #dd($data['wk_sales_period_to']);
        }

        $request->merge($data);
        return $next($request);
    }
}
