<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRegisterCheckRequest; #追加
use App\Http\Requests\ProductRegisterRequest; #追加
use App\ProductMasters;                         #追加
use Illuminate\Support\Facades\Storage;         #追加

class ProductRegisterController extends Controller
{
    /**
     * 商品情報登録（入力）へ表示する。
     */
    public function registerIn(Request $request){
        return view('operator.menu.product_register_in');
    }

    /**
     * 商品情報登録（入力）画面の入力内容をチェック後、
     * 画像ファイルのtempフォルダへの保存、入力内容をセッションに保存し
     * 次のルートへリダイレクトする。
     */
    public function registerCheck(ProductRegisterCheckRequest $request){
        #クエリーを配列で取得(画像ファイルの2項目は直接配列に含められないため除外)
        $data = $request->except(['product_image', 'product_thumbnail']);

        ### 商品画像ファイルの処理
        # 1.クライアント側のファイル名をリクエストに保存
        # 2.サーバー側のファイル名をリクエストに保存（商品コード＋_product_image_＋タイムスタンプ＋拡張子）
        # 3.上記2.のファイル名で添付フォルダ（storage/app/public/tmep）へ保存
        # 4.上記のファイルにクライアントからアクセスするパスをリクエストに保存　※クライアント側からアクセスできるpublicにはstorage/appへのシンボルがある
        $data['wk_product_image_original_filename'] = $request->file('product_image')->getClientOriginalName();
        $data['wk_product_image_filename'] = $data['product_code']."_product_image_".time().".".$request->file('product_image')->getClientOriginalExtension();
        $request->file('product_image')
                ->storeAs('public/temp/',$data['wk_product_image_filename']);
        $data['wk_product_image_pathname_client'] = "/storage/temp/".$data['wk_product_image_filename'];
        ### 商品サムネイルファイルの処理
        # 上記1.〜4.参照
        $data['wk_product_thumbnail_original_filename'] = $request->file('product_thumbnail')->getClientOriginalName();
        $data['wk_product_thumbnail_filename'] = $data['product_code']."_product_thumbnail_".time().".".$request->file('product_thumbnail')->getClientOriginalExtension();
        $request->file('product_thumbnail')
                ->storeAs('public/temp/',$data['wk_product_thumbnail_filename']);
        $data['wk_product_thumbnail_pathname_client'] = "/storage/temp/".$data['wk_product_thumbnail_filename'];

        #上述の追加項目をリクエストに反映させる
        $request->merge($data);

        #セッションにリクエストを保存(画像ファイルの2項目は直接配列に含められないため除外)
        $request->session()->put('product_register_in_request',$request->except(['product_image', 'product_thumbnail']));

        #このルートはPOSTメソッドで稼働する。一度GETメソッドのルートへリダイレクトしてから
        #オペレーター画面へ戻す。（次画面でのバリデーションエラーで戻れるのはGETメソッドの画面のみのため）
        return redirect('/operator/product/register/checkview');
    }
    /**
     * 商品情報登録（確認）画面を表示する。
     */
    public function registerCheckView(Request $request){
        $data = $request->session()->get('product_register_in_request');    #商品情報登録（入力）の入力情報をセッションより取得
        return view('operator.menu.product_register_check',$data);
    }

    /**
     * 入力された商品情報を、商品情報マスタ（product_masters）テーブルへ登録し、
     * 画像ファイルをtempフォルダよりそれぞれの正式な保存先へ移動させる。
     * また、セッションの再作成を行い、商品情報登録（結果）画面を表示する。
     */
    public function register(ProductRegisterRequest $request){
        #商品情報登録（入力）画面のリクエストをセッションより取得
        $data = $request->session()->get('product_register_in_request');

        #商品画像、商品サムネイルをtempフォルダよりそれぞれ移動(プロジェクトtop/storage/app/public/配下のぞれぞれのフォルダへ保存)
        Storage::move('public/temp/'.$data['wk_product_image_filename'], 'public/product_image/'.$data['wk_product_image_filename']);
        Storage::move('public/temp/'.$data['wk_product_thumbnail_filename'], 'public/product_thumbnail/'.$data['wk_product_thumbnail_filename']);

        #商品情報マスタ（product_masters）のモデル作成→値を編集→保存
        $model = new ProductMasters;
        $model->product_code = $data['product_code'];
        $model->sales_period_from = $data['wk_sales_period_from'];
        $model->sales_period_to = $data['wk_sales_period_to'];
        $model->product_name = $data['product_name'];
        $model->product_description = $data['product_description'];
        $model->product_price = $data['product_price'];
        $model->product_image =  'public/product_image/'.$data['wk_product_image_filename'];
        $model->product_thumbnail =  'public/product_thumbnail/'.$data['wk_product_thumbnail_filename'];
        $model->product_search_keyword = $data['product_search_keyword'];
        $model->product_tag = $data['product_tag'];
        $model->product_stock_quantity = $data['product_stock_quantity'];
        $model->status = "仮登録";
        $model->selling_discontinued_classification = "";
        $model->temporary_updater_operator_code = "";
        $model->temporary_update_approver_operator_code = "";
        $model->save();

        #テーブル登録後の後処理
        #二重送信対策(セッションの再作成)
        $request->session()->regenerateToken();
        #登録結果画面を表示させる
        return view('operator.menu.product_register_result');
    }
}