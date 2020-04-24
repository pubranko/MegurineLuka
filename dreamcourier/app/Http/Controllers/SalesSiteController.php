<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductMaster;                      #追加
use App\TagMaster;                          #追加
use App\Http\Requests\ProductMasterIdCheckRequest;   #追加

class SalesSiteController extends Controller
{
    /**
     * タグマスタより対象となるタグごとの商品情報レコードを取得し、サイトトップページへ表示する。
     */
    public function siteTop(Request $request){

        $tag_queries = TagMaster::query();                              #タグマスタ
        $tag_queries->Where('validity_period_from','<=',now());         #現在、有効期間中の紹介タグをselect
        $tag_queries->Where('validity_period_to','>',now());
        $tag_queries->Where('status','正式');                           #正式に登録されているものをselect
        $tag_queries->Where('tag_level','=',1);                         #タグLVが１(TOPページに表示する対象)に限定してselect

        $tag_queries = $tag_queries->orderBy('priority', 'asc')->get(); #表示の優先度順に取得

        $wk_lists = [];                                                 #初期化
        foreach($tag_queries as $tag_query){                            #ここのループ：top→複数回、タグ別→１回のみ
            $wk_lists[] = $this->productListsCreate('top',$tag_query->children_tag,$tag_query->introduction_tag);
        }

        $wk_side_bar_lists = $this->sideBarListCreate();                #サイドバーに表示する情報を取得

        return view('member.site_product_lists',['wk_lists' => $wk_lists,'wk_side_bar_lists'=>$wk_side_bar_lists]);
        /* 呼び出し元へ渡すデータ構造
            $wk_lists = [[
                    'introduction_tag'=>キャンペーン等のカテゴリ名称,
                    '$wk_products'=>[1カテゴリに含まれる商品レコードの配列]
                    'links'=>topはnull、それ以外はページネイトのlinks()
                ],〜略〜
            ],
            $wk_side_bar_lists = [タグLV1の配列]
        */
    }

    /**
     * 商品のキーワード、または、タグによる絞り込み検索を行い、結果を表示する。
     */
    public function productSearch(Request $request){
        $wk_lists = [];    #初期化
        $page = $request->get('page');  #ページ指定がある場合、
        if(isset($page)){
            $search_word = $request->session()->get('product_search');
        }else{                          #ページ指定がない場合（初回）
            $search_word = $request->all();
        }

        if(isset($search_word['product_search_keyword'])){          #検索キーワードがあった場合
            $wk_lists[] = $this->productListsCreate('keyword',$search_word['product_search_keyword'],'');
        }else{                                                      #検索タグがあった場合
            $wk_lists[] = $this->productListsCreate('tag',$search_word['product_search_tag'],'');
        }

        $wk_side_bar_lists = $this->sideBarListCreate();            #サイドバーに表示する情報を取得

        if(!isset($page)){  #ページ指定がない場合（初回）、セッションに検索キーワードを保存
            $request->session()->put('product_search',$request->all());
        }

        return view('member.site_product_lists',['wk_lists' => $wk_lists,'wk_side_bar_lists'=>$wk_side_bar_lists]);
    }

    /**
     * 選択された商品の詳細情報ページを表示する。
     */
    public function productShow(ProductMasterIdCheckRequest $request){
        $id = $request->get('id');

        $wk_product = ProductMaster::find($id);                                             #対象の商品情報を取得

        $product_tag_convert = str_replace('　',' ',$wk_product['product_tag']);            #全角の空白は半角の空白へ置き換え
        $wk_product['wk_product_tag_lists'] = explode(' ',$product_tag_convert);            #半角の空白で分割した商品タグ配列を生成

        $wk_product['wk_product_image'] = $wk_product->productImagePath();                  #商品画像のパスをクライアント側用に加工
        $wk_product['wk_product_stock_quantity_status'] = $wk_product->productStockStatus();#商品販売状況を取得

        $cart_add_flg = $request->session()->get('cart_add_flg');
        if($cart_add_flg =='on'){                           #カートへ追加後のリダイレクトの場合
            $wk_product['cart_add_flg'] = $cart_add_flg;    #「購入手続きへ」ボタンをページに表示させる。
            $request->session()->forget('cart_add_flg');    #セッションよりcart_add_flgを削除
        }

        $wk_side_bar_lists = $this->sideBarListCreate();    #サイドバーに表示する情報を取得

        return view('member.site_product',['wk_product'=>$wk_product,'wk_side_bar_lists'=>$wk_side_bar_lists]);
    }

    /**
     * 商品販売ページのサイドバーに表示させるカテゴリー情報リストを生成する。
     */
    private function sideBarListCreate(){
        $tag_queries = TagMaster::query();                          #タグマスタ
        $tag_queries->Where('validity_period_from','<=',now());     #現在、有効期間中の紹介タグをselect
        $tag_queries->Where('validity_period_to','>',now());
        $tag_queries->Where('status','正式');                       #正式に登録されているものをselect
        $tag_queries->Where('tag_level','=',1);                     #タグLVが１(TOPページに表示する対象)に限定してselect

        $tag_queries = $tag_queries->orderBy('priority', 'asc')->get();   #表示の優先度順に取得
        foreach($tag_queries as $tag_query){
            $wk_side_bar_lists[] = $tag_query->product_tag;
        }
        return $wk_side_bar_lists;
    }

    /**
     * 商品情報マスタより商品情報を取得し、商品の一覧のリストを生成する。
     *
     * 生成パターン($create_pattern)に応じた処理を実施する。
     *   以下の３パターンとする。
     *   'top'：トップページ用の処理
     *   'tag'：指定されたタグページ用の処理
     *   'keyword'：指定されたキーワード用の処理
     * サーチワード($search_word)には、呼び出しもとから、検索用のタグ情報、またはキーワード情報が格納されている。
     * 紹介タグ($introduction_tag)には、'top'でのみ使用する。タグマスタテーブル(tag_masters)の紹介タグが格納されている。
     */
    private function productListsCreate($create_pattern,$search_word,$introduction_tag){
        ##########################
        #商品情報マスタのクエリー
        ##########################
        $product_queries = ProductMaster::query();                    #商品情報マスタ
        $product_queries->Where('sales_period_from','<=',now());      #現在、販売期間中の商品をselect
        $product_queries->Where('sales_period_to','>',now());
        $product_queries->Where('status','正式');                     #正式に登録されているものをselect

        if($create_pattern == 'top'){        #ページTOP(HOME)の場合
            $tag_convert = str_replace('　',' ',$search_word);                  #全角の空白は半角の空白へ置き換え
            $product_tag_lists = explode(' ',$tag_convert);                     #半角の空白で分割した商品タグ配列を生成
            $product_queries->where(function($query) use($product_tag_lists){   #いずれかの単語を含むレコードを商品情報マスタから取得する。
                foreach($product_tag_lists as $tag){
                        $query->orWhere('product_tag','like',$tag,);
                }
            });
        }

        if($create_pattern == 'keyword'){    #検索キーワードがあった場合
            $product_search_keyword_convert = str_replace('　',' ',$search_word);                               #全角の空白は半角の空白へ置き換え
            $product_search_keyword_convert = '%'.str_replace(' ','% %',$product_search_keyword_convert).'%';   #先頭・末尾・空白の前後に%(ワイルドカード)を付与
            $product_search_keyword_lists = explode(' ',$product_search_keyword_convert);                       #半角の空白で分割した商品検索キーワード配列を生成
            $product_queries->where(function($query) use($product_search_keyword_lists){                        #いずれかの単語を含むレコードを商品情報マスタから取得する。
                foreach($product_search_keyword_lists as $keyword){
                        $query->orWhere('product_search_keyword','like',$keyword,);
                }
            });
        }

        if($create_pattern == 'tag'){                               #検索タグがあった場合
            $tag_query = TagMaster::query();                        #タグマスタ
            $tag_query->Where('validity_period_from','<=',now());   #現在、有効期間中の紹介タグをselect
            $tag_query->Where('validity_period_to','>',now());
            $tag_query->Where('status','正式');                     #正式に登録されているものをselect
            $tag_query->Where('product_tag','=',$search_word);
            $tag_query = $tag_query->first();

            #タグLV=1（親タグ）の場合、子タグに該当する商品を、商品情報マスタから取得。
            #タグLV=2（子タグ）の場合、指定されたタグの商品を、商品情報マスタから取得。
            if($tag_query->tag_level == 1){
                $tag_convert = str_replace('　',' ',$tag_query->children_tag);      #全角の空白は半角の空白へ置き換え
                $product_tag_lists = explode(' ',$tag_convert);                     #半角の空白で分割した商品タグ配列を生成
                $product_queries->where(function($query) use($product_tag_lists){   #いずれかの単語を含むレコードを商品情報マスタから取得する。
                    foreach($product_tag_lists as $tag){
                            $query->orWhere('product_tag','=',$tag,);
                    }
                });
            }else{
                $product_queries->Where('product_tag','=',$search_word);   #対象のタグで絞り込み
            }
        }

        if($create_pattern == 'top'){                           #TOPページの場合
            $product_lists = $product_queries->orderBy('created_at','desc')->limit(10)->get();
        }else{                                                  #タグ・キーワード検索の場合
            $product_lists = $product_queries->paginate(15);
        }

        ####################################################################
        #商品情報マスタのクエリーからページに表示するための情報を取りまとめ
        ####################################################################
        $wk_products = [];                      #初期化
        $cnt = $product_lists->count();         #取得した商品情報レコードの件数分繰り返す
        for ($i=0;$i<$cnt;++$i){
            $wk_product = $product_lists[$i]->toArray();                                                #クエリーから商品情報レコードの連想配列を取得
            $wk_product['wk_product_thumbnail'] = $product_lists[$i]->productThumbnailPath();           #サムネイルのパスをクライアント側用に加工
            $wk_product['wk_product_stock_quantity_status'] = $product_lists[$i]->productStockStatus(); #商品販売状況を取得
            $wk_products[] = $wk_product;                                                               #1商品の情報を配列に追加
        }
        $wk_lists['wk_products'] = $wk_products;                                                         #１商品の情報をまとめて格納

        if($create_pattern == 'top'){                           #TOPページの場合
            $wk_lists['introduction_tag'] = $introduction_tag;   #各カテゴリーの紹介タグ
            $wk_lists['links'] = null;
        }
        if($create_pattern == 'keyword'){                       #検索キーワードがあった場合
            $wk_lists['introduction_tag'] = 'キーワード：'.$search_word.'の検索結果';
            $wk_lists['links'] = $product_lists->links();
        }
        if($create_pattern == 'tag'){                           #検索タグがあった場合
            $wk_lists['introduction_tag'] = 'タグ：'.$search_word.'の検索結果';
            $wk_lists['links'] = $product_lists->links();
        }

        return $wk_lists;
    }
}