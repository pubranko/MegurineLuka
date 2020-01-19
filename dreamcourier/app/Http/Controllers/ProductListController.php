<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductMaster;                     #追加
use App\Http\Requests\ProductShowRequest; #追加

class ProductListController extends Controller
{
    /**
     * 商品のキーワードによる絞り込み検索を行い、結果を表示する。
     */
    public function productSearch(Request $request){


        $wk_lists = [];    #初期化

        $product_queries = ProductMaster::query();                    #商品情報マスタ
        $product_queries->Where("sales_period_from","<=",now());      #現在、販売期間中の商品をselect
        $product_queries->Where("sales_period_to",">",now());
        $product_queries->Where("status","正式");                     #正式に登録されているものをselect

        $product_search_keyword_convert = str_replace("　"," ",$request->get("product_search_keyword"));    #全角の空白は半角の空白へ置き換え
        $product_search_keyword_convert = "%".str_replace(" ","% %",$product_search_keyword_convert)."%";   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
        $product_search_keyword_lists = explode(" ",$product_search_keyword_convert);                       #半角の空白で分割した商品検索キーワード配列を生成
        foreach($product_search_keyword_lists as $wk_keyword){
            $product_queries->Where("product_search_keyword","like","%".$wk_keyword."%");   #対象のキーワードで絞り込み
        }

        $product_lists = $product_queries->paginate(15);
        $links = $product_lists->links();

        $wk_products = [];  #初期化
        $cnt = $product_lists->count();   #取得した商品情報レコードの件数分繰り返す
        for ($i=0;$i<$cnt;++$i){

            $wk_product = $product_lists[$i]->toArray(); #クエリーから商品情報レコードの連想配列を取得
            $wk_product['wk_product_thumbnail'] = str_replace("public","storage",$wk_product['product_thumbnail']);  #サムネイルのパスをクライアント側用に加工

            if($wk_product['selling_discontinued_classification']=="販売中止"){     #販売中止区分
                $wk_product['wk_product_stock_quantity_status'] = "販売中止";
            }elseif($wk_product['product_stock_quantity'] > 3){                     #商品在庫状況を追加
                $wk_product['wk_product_stock_quantity_status'] = "在庫あり";
            }elseif($wk_product['product_stock_quantity'] > 0){
                $wk_product['wk_product_stock_quantity_status'] = "在庫あとわずか！";
            }else{
                $wk_product['wk_product_stock_quantity_status'] = "在庫なし";
            }
            $wk_products[] = $wk_product;   #1商品の情報を配列に追加
        }
        $wk_list["wk_products"] = $wk_products;                             #１商品の情報をまとめて格納
        $wk_list["introduction_tag"] = $request->get("product_search_keyword")."の検索結果";   #入力された検索キーワード
        $wk_list["links"] = $links;                                         #タグ別ページの場合、paginateのlinks情報を格納

        $wk_lists[] = $wk_list;    #まとめた１カテゴリー単位の情報を配列へ格納

        /* 呼び出し元へ渡すデータ構造
            $wk_lists = [[
                    "introduction_tag"=>キャンペーン等のカテゴリ名称,
                    "$wk_products"=>[1カテゴリに含まれる商品レコードの配列]
                    "links"=>topはnull、それ以外はページネイトのlinks()
                ],〜略〜
            ]*/
        #return $wk_lists;
        return view('member.site_product_lists',["wk_lists" => $wk_lists]);
    }

    /**
     * 選択された商品の詳細情報を表示する。
     */
    public function productShow(ProductShowRequest $request){
        $id = $request->get('id');

        $product_queries = ProductMaster::query();                    #商品情報マスタ
        $wk_product = $product_queries->find($id);

        $product_tag_convert = str_replace("　"," ",$wk_product['product_tag']);    #全角の空白は半角の空白へ置き換え
        $wk_product['wk_product_tag_lists'] = explode(" ",$product_tag_convert);                     #半角の空白で分割した商品タグ配列を生成

        $wk_product['wk_product_image'] = str_replace("public","storage",$wk_product['product_image']);  #商品画像のパスをクライアント側用に加工

        if($wk_product['selling_discontinued_classification']=="販売中止"){     #販売中止区分
            $wk_product['wk_product_stock_quantity_status'] = "販売中止";
        }elseif($wk_product['product_stock_quantity'] > 3){                     #商品在庫状況を追加
            $wk_product['wk_product_stock_quantity_status'] = "在庫あり";
        }elseif($wk_product['product_stock_quantity'] > 0){
            $wk_product['wk_product_stock_quantity_status'] = "在庫あとわずか！";
        }else{
            $wk_product['wk_product_stock_quantity_status'] = "在庫なし";
        }

        $cart_add_flg = $request->session()->get('cart_add_flg');
        #カートへ追加後のリダイレクトの場合
        if($cart_add_flg =="on"){
            $wk_product['cart_add_flg'] = $request->session()->get('cart_add_flg');
            $request->session()->forget('cart_add_flg');        #セッションよりcart_add_flgを削除
        }

        return view('member.site_product',$wk_product);
    }
}