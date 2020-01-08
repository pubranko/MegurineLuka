<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMasters extends Model
{
    #Scope
    #販売期間の重複チェック
    #$from≦〜＜$to（今回の入力値）
    #sales_period_from≦〜＜sales_period_to（テーブルの登録値）
    public function scopeSalesPeriodDuplicationCheck($query, $from, $to) {

        $query->where(function($q) use($from, $to) { // 解説 - 1

            #dd('aaa'.$from.' '.$to);
            $q->where('sales_period_from', '>=', $from)
                ->where('sales_period_from', '<', $to);

        })
        ->orWhere(function($q) use($from, $to) { // 解説 - 2
            #dd('bbb'.$from.' '.$to);
            $q->where('sales_period_to', '>', $from)
                ->where('sales_period_to', '<=', $to);

        })
        ->orWhere(function($q) use ($from, $to) { // 解説 - 3
            #dd('ccc'.$from.' '.$to);
            $q->where('sales_period_from', '<', $from)
                ->where('sales_period_to', '>', $to);

        });
        #$a = $query->first();
        #dd($a);

    }
}
