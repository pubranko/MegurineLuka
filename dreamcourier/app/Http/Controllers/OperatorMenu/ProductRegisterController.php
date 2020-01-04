<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRegisterCheckRequest; #追加

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

}
