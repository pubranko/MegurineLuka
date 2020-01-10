<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductSearchRequest; #追加
use App\ProductMasters;                     #追加

class ProductReferenceController extends Controller
{
    /**
     * 商品_検索画面で入力された値より商品情報マスタ（product_masters）を検索した値を表示する。
     */
    public function search(ProductSearchRequest $request){

        ### 初画面表示
        if(isset($request['first_flg'])){   #オペレーターメニュー画面より遷移してきた場合、テーブル検索の実行を回避
            $search_queries = [];
            $item  = array_merge($request->all(),['search_queries' => $search_queries]);
            return view('operator.menu.product_search',$item);
        }
        ### 以下画面より入力された条件を満たすレコードを検索
        $query = ProductMasters::query();
        if(!empty($request->get('product_code'))){              #商品コード
            $product_code_convert = str_replace("　"," ",$request->get('product_code'));    #全角の空白は半角の空白へ置き換え
            $product_code_convert = "%".str_replace(" ","% %",$product_code_convert)."%";   #単語の前後に%(ワイルドカード)を付与
            $product_code_lists = explode(" ",$product_code_convert);                       #半角の空白で分割した商品コード配列を生成
            $query->where(function($query) use($product_code_lists){                        #いずれかの単語を含むレコードを取得する。
                foreach($product_code_lists as $code){
                        $query->orWhere("product_code","like",$code,);
                }
            });
        }
        if(!empty($request->get('product_search_keyword'))){    #商品検索キーワード
            $product_search_keyword_convert = str_replace("　"," ",$request->get('product_search_keyword'));    #全角の空白は半角の空白へ置き換え
            $product_search_keyword_convert = "%".str_replace(" ","% %",$product_search_keyword_convert)."%";   #単語の前後に%(ワイルドカード)を付与
            $product_search_keyword_lists = explode(" ",$product_search_keyword_convert);                       #半角の空白で分割した商品コード配列を生成
            $query->where(function($query) use($product_search_keyword_lists){                                  #いずれかの単語を含むレコードを取得する。
                foreach($product_search_keyword_lists as $keyword){
                        $query->orWhere("product_search_keyword","like",$keyword,);
                }
            });
        }
        if(!empty($request->get('product_tag'))){                    #商品タグ
            #$query->where('product_tag','like',"%".$request->get('product_tag')."%");
            $product_tag_convert = str_replace("　"," ",$request->get('product_tag'));    #全角の空白は半角の空白へ置き換え
            $product_tag_convert = "%".str_replace(" ","% %",$product_tag_convert)."%";   #単語の前後に%(ワイルドカード)を付与
            $product_tag_lists = explode(" ",$product_tag_convert);                       #半角の空白で分割した商品コード配列を生成
            $query->where(function($query) use($product_tag_lists){                       #いずれかの単語を含むレコードを取得する。
                foreach($product_tag_lists as $tag){
                        $query->orWhere("product_tag","like",$tag,);
                }
            });
        }
        if(!empty($request->get('product_stock_quantity_from'))){    #商品在庫数（以上）
            $query->where('product_stock_quantity','>=',$request->get('product_stock_quantity_from'));
        }
        if(!empty($request->get('product_stock_quantity_to'))){      #商品在庫数（以下）
            $query->where('product_stock_quantity','<=',$request->get('product_stock_quantity_to'));
        }
        if(!empty($request->get('sales_period_date_from')) ||        #販売期間FROM~TO
           !empty($request->get('sales_period_date_to'))){
            $query->SalesPeriodDuplicationCheck($request->get('wk_sales_period_from'), $request->get('wk_sales_period_to'));
        }
        if(!empty($request->get('seles_status'))){                   #販売状況
            $query->whereIn('seles_status',$request->get('seles_status'));
        }
        if(!empty($request->get('status'))){                         #ステータス
            $query->whereIn('status',$request->get('status'));
        }

        $search_queries = $query->paginate($request->get('product_list_details'));
        $item  = array_merge($request->all(),['search_queries' => $search_queries]);
        return view('operator.menu.product_search',$item);
    }

    /**
     * 商品情報の詳細を表示する。
     */
    public function show(Request $request){
        $id = $request->get("id");          #指定されたIDより商品情報マスタを取得する。
        $query = ProductMasters::find($id);
        #テーブルに登録されている商品画像・商品サムネイルのファイルパスを、クライアント側からアクセスするパスへ変換する。
        $query['product_image'] = str_replace("public","/storage",$query['product_image']);
        $query['product_thumbnail'] = str_replace("public","/storage",$query['product_thumbnail']);
        return view('operator.menu.product_show',$query);
    }
}
