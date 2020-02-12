<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    /**
     * 販売期間の重複チェック
     * $from≦〜＜$to（今回の入力値）
     * sales_period_from≦〜＜sales_period_to（テーブルの登録値）
     */
    public function scopeSalesPeriodDuplicationCheck($query, $from, $to) {
        $query->where(function($q) use($from, $to) {
            $q->where('sales_period_from', '>=', $from)
                ->where('sales_period_from', '<', $to);
        })
        ->orWhere(function($q) use($from, $to) {
            $q->where('sales_period_to', '>', $from)
                ->where('sales_period_to', '<=', $to);
        })
        ->orWhere(function($q) use ($from, $to) {
            $q->where('sales_period_from', '<', $from)
                ->where('sales_period_to', '>', $to);
        });
    }

    /**
     * 商品在庫リストより商品在庫数を取得する。
     */
    public function productStockQuantity(){
        #return $this->hasOne('App\ProductStockList','product_code','product_code')->product_stock_quantity;    #商品在庫リストの商品コードと当テーブルの商品コードを紐付け、商品在庫数をリターン
        return $this->hasOne('App\ProductStockList','product_code','product_code');    #商品在庫リストの商品コードと当テーブルの商品コードを紐付け
    }
    #public function productMaster(){
    #    return $this->hasOne('App\ProductMaster','id','product_id');    #productMasterのidと、カートリストのproduct_idを紐付け
    #}

}
