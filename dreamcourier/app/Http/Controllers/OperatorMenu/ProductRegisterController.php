<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRegisterCheckRequest; #追加
use App\Http\Requests\ProductRegisterRequest; #追加
use App\ProductMasters;

class ProductRegisterController extends Controller
{
    //
    public function registerMenu(Request $request){
        if($request->get('product_menu')=="登録"){
            return redirect('/operator/product/in');
        }elseif($request->get('product_menu')=="検索"){
            return redirect('/operator/product/search');
        }
        #return view('operator.menu.product_register_in');
    }
    public function registerIn(Request $request){
        return view('operator.menu.product_register_in');
    }

    public function registerCheck(ProductRegisterCheckRequest $request){
        $request->session()->put('product_register_in_request',$request->except(['product_image', 'product_thumbnail']));    #セッションにリクエストを保存

        return view('operator.menu.product_register_check',$request->except(['product_image', 'product_thumbnail']));
    }

    public function register(ProductRegisterRequest $request){
        #商品登録（入力）のリクエストを取得
        $data = $request->session()->get('product_register_in_request');

        $model = new ProductMasters;

        $model->product_code = $data['product_code'];
        $model->sales_period_from = $data['wk_sales_period_from'];
        $model->sales_period_to = $data['wk_sales_period_to'];
        $model->product_name = $data['product_name'];
        $model->product_description = $data['product_description'];
        $model->product_price = $data['product_price'];
        $model->product_image =  $data['wk_product_image_pathname_server'];       #$data['product_image'];
        $model->product_thumbnail = $data['wk_product_thumbnail_pathname_server'];
        $model->product_search_keyword = $data['product_search_keyword'];
        $model->product_tag = $data['product_tag'];
        $model->product_stock_quantity = $data['product_stock_quantity'];
        $model->status = "仮登録";
        $model->selling_discontinued_classification = "";
        $model->temporary_updater_operator_code = "";
        $model->temporary_update_approver_operator_code = "";

        $model->save();

        return view('operator.menu.product_register_result');
    }
}
