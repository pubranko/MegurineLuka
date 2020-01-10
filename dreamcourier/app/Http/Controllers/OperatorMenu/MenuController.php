<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * オペレーターメニュー画面で押下されたボタンによってリダイレクト先を分岐させる
     */
    public function ProductMenu(Request $request){
        if($request->get('product_menu')=="登録"){
            return redirect('/operator/product/register/in');
        }elseif($request->get('product_menu')=="検索"){
            return redirect('/operator/product/search?first_flg=on');
        }
    }

}
