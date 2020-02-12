<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductSearchRequest; #追加
use App\ProductMaster;                     #追加
use App\ProductStockList;                     #追加
use Illuminate\Support\Facades\DB;  #追加

class ProductReferenceController extends Controller
{
    /**
     * 商品_検索画面で入力された値より商品情報マスタ（product_masters）を検索した値を表示する。
     */
    public function search(ProductSearchRequest $request){
        ################
        ### 初画面表示
        ################
        if(isset($request['first_flg'])){   #オペレーターメニュー画面より遷移してきた場合、テーブル検索の実行を回避
            $search_queries = [];
            $item  = array_merge($request->all(),['search_queries' => $search_queries]);
            return view('operator.menu.product_search',$item);
        }

        ######################################################
        ### 以下画面より入力された条件を満たすレコードを検索
        ######################################################
        #商品情報マスタに、商品在庫リストの在庫数をjoinで付与する。
        $query = DB::table('product_masters')
                    ->join('product_stock_lists','product_masters.product_code','=','product_stock_lists.product_code')
                    ->select('product_masters.*','product_stock_lists.product_stock_quantity');

        if(!empty($request->get('product_code'))){              #商品コード
            $product_code_convert = str_replace("　"," ",$request->get('product_code'));    #全角の空白は半角の空白へ置き換え
            $product_code_convert = "%".str_replace(" ","% %",$product_code_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_code_lists = explode(" ",$product_code_convert);                       #半角の空白で分割した商品コード配列を生成
            $query->where(function($query) use($product_code_lists){                        #いずれかの単語を含むレコードを取得する。
                foreach($product_code_lists as $code){
                        $query->orWhere("product_masters.product_code","like",$code,);
                }
            });
        }
        if(!empty($request->get('product_search_keyword'))){    #商品検索キーワード
            $product_search_keyword_convert = str_replace("　"," ",$request->get('product_search_keyword'));    #全角の空白は半角の空白へ置き換え
            $product_search_keyword_convert = "%".str_replace(" ","% %",$product_search_keyword_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_search_keyword_lists = explode(" ",$product_search_keyword_convert);                       #半角の空白で分割した検索キーワード配列を生成
            $query->where(function($query) use($product_search_keyword_lists){                                  #いずれかの単語を含むレコードを取得する。
                foreach($product_search_keyword_lists as $keyword){
                        $query->orWhere("product_search_keyword","like",$keyword,);
                }
            });
        }
        if(!empty($request->get('product_tag'))){                    #商品タグ
            $product_tag_convert = str_replace("　"," ",$request->get('product_tag'));    #全角の空白は半角の空白へ置き換え
            $product_tag_convert = "%".str_replace(" ","% %",$product_tag_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_tag_lists = explode(" ",$product_tag_convert);                       #半角の空白で分割した商品タグ配列を生成
            $query->where(function($query) use($product_tag_lists){                       #いずれかの単語を含むレコードを取得する。
                foreach($product_tag_lists as $tag){
                        $query->orWhere("product_tag","like",$tag,);
                }
            });
        }
        if($request->get('product_stock_quantity_from')!==""){    #商品在庫数（以上）
            $query->where('product_stock_quantity','>=',$request->get('product_stock_quantity_from'));
        }
        if($request->get('product_stock_quantity_to')!==""){      #商品在庫数（以下）
            $query->where('product_stock_quantity','<=',$request->get('product_stock_quantity_to'));
        }
        if(!empty($request->get('sales_period_date_from')) ||        #販売期間FROM~TO
           !empty($request->get('sales_period_date_to'))){
            $from = $request->get('wk_sales_period_from');
            $to = $request->get('wk_sales_period_to');
            $query->where(function($query) use($from,$to){
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
            });
        }
        if(!empty($request->get('selling_discontinued_classification'))){  #販売中止区分
            $query->whereIn('selling_discontinued_classification',$request->get('selling_discontinued_classification'));
        }
        if(!empty($request->get('status'))){                         #ステータス
            $query->whereIn('status',$request->get('status'));
        }

        #１ページに出力する明細を取得し、マージした配列をビューへ渡す。
        $search_queries = $query->paginate($request->get('product_list_details'));

        $item  = array_merge($request->all(),['search_queries' => $search_queries]);
        #承認画面(ProductApproval)から遷移してきた場合、リクエストをセッションより取得し復元する。
        $item2 = $request->session()->get('product_approval_request');
        if(isset($item2)){
            $item  = array_merge($item,$item2);
            $request->session()->forget('product_approval_request');
        }

        return view('operator.menu.product_search',$item);
    }

    /**
     * 商品情報の詳細を表示する。
     */
    public function show(Request $request){
        $id = $request->get("id");          #指定されたIDより商品情報マスタを取得する。
        $query = ProductMaster::find($id);
        #テーブルに登録されている商品画像・商品サムネイルのファイルパスを、クライアント側からアクセスするパスへ変換する。
        $query['product_image'] = str_replace("public","/storage",$query['product_image']);
        $query['product_thumbnail'] = str_replace("public","/storage",$query['product_thumbnail']);

        return view('operator.menu.product_show',$query);
    }
}
