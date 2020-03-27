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

        $featured_queries = FeaturedProductMaster::query(); #注目商品マスタ
        $featured_queries->Where("validity_period_from","<=",now());    #現在、有効期間中の紹介タグをselect
        $featured_queries->Where("validity_period_to",">",now());
        $featured_queries->Where("status","正式");                      #正式に登録されているものをselect
        $featured_queries->Where("priority","<",100);                   #優先度順が１００未満(TOPページに表示する対象)に限定してselect

        $featured_queries = $featured_queries->orderBy('priority', 'asc')->get();   #表示の優先度順に取得

        $wk_lists = [];    #初期化
        foreach($featured_queries as $featured_query){        #ここのループ：top→複数回、タグ別→１回のみ

            $product_queries = ProductMaster::query();                    #商品情報マスタ
            $product_queries->Where("product_tag","like","%".$featured_query->product_tag."%");   #対象のタグをもつ商品のみselect
            $product_queries->Where("sales_period_from","<=",now());      #現在、販売期間中の商品をselect
            $product_queries->Where("sales_period_to",">",now());
            $product_queries->Where("status","正式");                     #正式に登録されているものをselect

            $product_lists = $product_queries->get();
            $links = null;

            $wk_products = [];  #初期化
            $cnt = $product_lists->count();   #取得した商品情報レコードの件数分繰り返す
            for ($i=0;$i<$cnt;++$i){

                #商品在庫リストより商品在庫数を取得
                $wk_stock = $product_lists[$i]->productStockList;

                $wk_product = $product_lists[$i]->toArray(); #クエリーから商品情報レコードの連想配列を取得
                $wk_product['wk_product_thumbnail'] = $product_lists[$i]->productThumbnailPath();  #サムネイルのパスをクライアント側用に加工
                $wk_product['wk_product_stock_quantity_status'] = $product_lists[$i]->productStockStatus(); #商品販売状況を確認

                $wk_products[] = $wk_product;   #1商品の情報を配列に追加
            }
            $wk_list["wk_products"] = $wk_products;                             #１商品の情報をまとめて格納
            $wk_list["introduction_tag"] = $featured_query->introduction_tag;   #対になる紹介タグを格納
            $wk_list["links"] = $links;                                         #タグ別ページの場合、paginateのlinks情報を格納

            $wk_lists[] = $wk_list;    #まとめた１カテゴリー単位の情報を配列へ格納
        }

        return view('member.site_top',["wk_lists" => $wk_lists]);
        /* 呼び出し元へ渡すデータ構造
            $wk_lists = [[
                    "introduction_tag"=>キャンペーン等のカテゴリ名称,
                    "$wk_products"=>[1カテゴリに含まれる商品レコードの配列]
                    "links"=>topはnull、それ以外はページネイトのlinks()
                ],〜略〜
            ]*/
    }
}