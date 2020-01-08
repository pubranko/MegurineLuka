<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ProductSearchRequest; #追加
use App\ProductMasters;                     #追加

class ProductReferenceController extends Controller
{
    /**
     * 商品_検索画面を表示する。
     */
    #public function searchIn(Request $request){
    #    return view('operator.menu.product_search');
    #}
    /**
     * 商品_検索画面で入力された値より商品情報マスタ（product_masters）を検索した値を表示する。
     */
    public function search(ProductSearchRequest $request){

        if(!isset($request['product_code'])){   #商品コードの項目自体がリクエストにない（初回表示時）場合、テーブル検索の実行を回避
            $search_queries = [];
            $item  = array_merge($request->all(),['search_queries' => $search_queries]);
            return view('operator.menu.product_search',$item);
        }

        $query = ProductMasters::query();
        if(!empty($request->get('product_code'))){              #商品コード
            $query->where('product_code','like',"%".$request->get('product_code')."%");
        }
        if(!empty($request->get('product_search_keyword'))){    #商品検索キーワード
            $query->where('product_search_keyword','like',"%".$request->get('product_search_keyword')."%");
        }
        if(!empty($request->get('product_tag'))){               #商品タグ
            $query->where('product_tag','like',"%".$request->get('product_tag')."%");
        }
        if(!empty($request->get('seles_status'))){              #販売状況
            #これDBにない項目：$query->where('seles_status',$request->get('seles_status'));
        }
        if(!empty($request->get('status'))){                    #ステータス
            $query->whereIn('status',$request->get('status'));
        }
        if(!empty($request->get('sales_period_date_from')) ||   #販売期間FROM~TO
           !empty($request->get('sales_period_date_to'))){
            $query->SalesPeriodDuplicationCheck($request->get('wk_sales_period_from'), $request->get('wk_sales_period_to'));
        }

        if(!empty($request->get('product_stock_quantity_from'))){    #商品在庫数
            $query->where('product_stock_quantity','>=',$request->get('product_stock_quantity_from'));
        }
        if(!empty($request->get('product_stock_quantity_to'))){    #商品在庫数
            $query->where('product_stock_quantity','<=',$request->get('product_stock_quantity_to'));
        }
        #プルダウンをマルチに
        #初回は検索しない
        #あとこれ
        #product_price
        #product_stock_quantity

        $search_queries = $query->paginate($request->get('product_list_details'));
        $item  = array_merge($request->all(),['search_queries' => $search_queries]);
        return view('operator.menu.product_search',$item);
    }

    /**
     * 
     */
    public function show(Request $request){
        return view('operator.menu.product_show');
    }

}
