<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductMaster;                     #追加
use App\FeaturedProductMaster;             #追加

class SalesSiteController extends Controller
{
    /**
     * 注目商品マスタより対象となるタグごとの商品情報レコードを取得し、サイトトップページへ表示する。
     */
    public function siteTop(Request $request){

        $wk_lists = $this->productInfomation($request,"site_top");

        return view('member.site_top',["wk_lists" => $wk_lists]);
    }

    /**
     * 指定されたタグの商品情報レコードを取得し、タグ別ページを表示する。
     */
    public function siteProduct(Request $request){

        $wk_lists = $this->productInfomation($request,"site_product");

        return view('member.site_product_lists',["wk_lists" => $wk_lists]);
    }
    /**
     * タグごとの情報を配列に取りまとめてreturnする。
     */
    private function productInfomation(Request $request,string $path){
        $featured_queries = FeaturedProductMaster::query(); #注目商品マスタ
        $featured_queries->Where("validity_period_from","<=",now());    #現在、有効期間中の紹介タグをselect
        $featured_queries->Where("validity_period_to",">",now());
        $featured_queries->Where("status","正式");                      #正式に登録されているものをselect
        if($path =="site_product"){
            $featured_queries->Where("product_tag",$request->get("tag"));      #タグ別のページの場合、指定されたタグのみselect
        }
        $featured_queries = $featured_queries->orderBy('priority', 'asc')->get();   #表示の優先度順に取得

        $wk_lists = [];    #初期化
        foreach($featured_queries as $featured_query){        #ここのループ：top→複数回、タグ別→１回のみ

            $product_queries = ProductMaster::query();                    #商品情報マスタ
            $product_queries->Where("product_tag","like","%".$featured_query->product_tag."%");   #対象のタグをもつ商品のみselect
            $product_queries->Where("sales_period_from","<=",now());      #現在、販売期間中の商品をselect
            $product_queries->Where("sales_period_to",">",now());
            $product_queries->Where("status","正式");                     #正式に登録されているものをselect

            if($path =="site_product"){
                $product_lists = $product_queries->paginate(15);
                $links = $product_lists->links();
            }else{
                $product_lists = $product_queries->get();
                $links = null;
            }

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
            $wk_list["introduction_tag"] = $featured_query->introduction_tag;   #対になる紹介タグを格納
            $wk_list["links"] = $links;                                         #タグ別ページの場合、paginateのlinks情報を格納

            $wk_lists[] = $wk_list;    #まとめた１カテゴリー単位の情報を配列へ格納
        }

        /* 呼び出し元へ渡すデータ構造
            $wk_lists = [[
                    "introduction_tag"=>キャンペーン等のカテゴリ名称,
                    "$wk_products"=>[1カテゴリに含まれる商品レコードの配列]
                    "links"=>topはnull、それ以外はページネイトのlinks()
                ],〜略〜
            ]*/
        return $wk_lists;

    }

}

