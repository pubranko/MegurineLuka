<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductSearchRequest; #追加
use App\Http\Requests\ProductMasterIdCheckRequest;   #追加
use App\ProductMaster;                     #追加
use App\Operator;                     #追加
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
        $page = $request->get('page');  #ページ指定がある場合、
        if(isset($page)){
            $search_word = $request->session()->get('product_search');
        }else{                          #ページ指定がない場合（初回）
            $search_word = $request->all();
        }

        #商品情報マスタに、商品在庫リストの在庫数をjoinで付与する。
        $query = DB::table('product_masters')
                    ->join('product_stock_lists','product_masters.product_code','=','product_stock_lists.product_code')
                    ->select('product_masters.*','product_stock_lists.product_stock_quantity');

        if(!empty($search_word['product_code'])){              #商品コード
            $product_code_convert = str_replace("　"," ",$search_word['product_code']);    #全角の空白は半角の空白へ置き換え
            $product_code_convert = "%".str_replace(" ","% %",$product_code_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_code_lists = explode(" ",$product_code_convert);                       #半角の空白で分割した商品コード配列を生成
            $query->where(function($query) use($product_code_lists){                        #いずれかの単語を含むレコードを取得する。
                foreach($product_code_lists as $code){
                        $query->orWhere("product_masters.product_code","like",$code,);
                }
            });
        }
        if(!empty($search_word['product_search_keyword'])){    #商品検索キーワード
            $product_search_keyword_convert = str_replace("　"," ",$search_word['product_search_keyword']);    #全角の空白は半角の空白へ置き換え
            $product_search_keyword_convert = "%".str_replace(" ","% %",$product_search_keyword_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_search_keyword_lists = explode(" ",$product_search_keyword_convert);                       #半角の空白で分割した検索キーワード配列を生成
            $query->where(function($query) use($product_search_keyword_lists){                                  #いずれかの単語を含むレコードを取得する。
                foreach($product_search_keyword_lists as $keyword){
                        $query->orWhere("product_search_keyword","like",$keyword,);
                }
            });
        }
        if(!empty($search_word['product_tag'])){                    #商品タグ
            $product_tag_convert = str_replace("　"," ",$search_word['product_tag']);    #全角の空白は半角の空白へ置き換え
            $product_tag_convert = "%".str_replace(" ","% %",$product_tag_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_tag_lists = explode(" ",$product_tag_convert);                       #半角の空白で分割した商品タグ配列を生成
            $query->where(function($query) use($product_tag_lists){                       #いずれかの単語を含むレコードを取得する。
                foreach($product_tag_lists as $tag){
                        $query->orWhere("product_tag","like",$tag,);
                }
            });
        }

        if($search_word['product_stock_quantity_from']!==""){    #商品在庫数（以上）
            $query->where('product_stock_quantity','>=',$search_word['product_stock_quantity_from']);
        }
        if($search_word['product_stock_quantity_to']!==""){      #商品在庫数（以下）
            $query->where('product_stock_quantity','<=',$search_word['product_stock_quantity_to']);
        }
        if(!empty($search_word['sales_period_date_from']) ||        #販売期間FROM~TO
           !empty($search_word['sales_period_date_to'])){
            $from = $search_word['wk_sales_period_from'];
            $to = $search_word['wk_sales_period_to'];
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
        if(!empty($search_word['selling_discontinued_classification'])){  #販売中止区分
            $query->whereIn('selling_discontinued_classification',$search_word['selling_discontinued_classification']);
        }
        if(!empty($search_word['status'])){                         #ステータス
            $query->whereIn('status',$search_word['status']);
        }

        #１ページに出力する明細を取得し、マージした配列をビューへ渡す。
        $search_queries = $query->paginate($search_word['product_list_details']);

        $item  = array_merge($request->all(),['search_queries' => $search_queries]);
        #承認画面(ProductApproval)から遷移してきた場合、リクエストをセッションより取得し復元する。
        $item2 = $request->session()->get('product_approval_request');
        if(isset($item2)){
            $item  = array_merge($item,$item2);
            $request->session()->forget('product_approval_request');
        }

        if(!isset($page)){  #ページ指定がない場合（初回）、セッションに検索キーワードを保存
            $request->session()->put('product_search',$request->all());
        }

        return view('operator.menu.product_search',$item);
    }

    /**
     * 商品情報マスタと関連情報の詳細を表示する。
     */
    public function show(ProductMasterIdCheckRequest $request){
        $id = $request->get("id");          #指定されたIDより商品情報マスタを取得する。
        $product = ProductMaster::find($id);
        #テーブルに登録されている商品画像・商品サムネイルのファイルパスを、クライアント側からアクセスするパスへ変換する。
        $product['product_image'] = $product->productImagePath();
        $product['product_thumbnail'] = $product->productThumbnailPath();

        #商品在庫リストより商品在庫数を取得
        $stock = $product->productStockList;

        #商品情報の仮更新者、仮更新承認者の名前を取得
        $temporary_updater_operator_name = '';
        if($product->temporary_updater_operator_code){
            $temporary_updater_operator_name  = Operator::where('operator_code',$product->temporary_updater_operator_code)->first()->name;
        }
        $temporary_update_approver_operator_name = '';
        if($product->temporary_update_approver_operator_code){
            $temporary_update_approver_operator_name  = Operator::where('operator_code',$product->temporary_update_approver_operator_code)->first()->name;
        }

        #商品情報マスタレコード、商品在庫数、仮更新者、仮更新承認者をビューへ渡す
        $item = [
            'product_master'=>$product,
            'product_stock_quantity'=>$stock['product_stock_quantity'],
            'temporary_updater_operator_name'=> $temporary_updater_operator_name,
            'temporary_update_approver_operator_name'=> $temporary_update_approver_operator_name,
        ];
        return view('operator.menu.product_show',$item);
    }
}
