<?php

namespace App\Http\Controllers\MemberMenu;

use App\Http\Controllers\Controller;
#use Illuminate\Http\Request;
use App\ProductCartList;                     #追加
use App\Http\Requests\ProductMasterIdCheckRequest; #追加
use App\Http\Requests\CartListIdCheckRequest; #追加
use Auth;   #追加


class ProductCartListController extends Controller
{
    /**
     * 選択された商品をカートリストへ登録する。
     */
    public function cartAdd(ProductMasterIdCheckRequest $request){
        $model = new ProductCartList;
        $model->product_id = $request->get('id');
        $model->member_code = Auth::user()->member_code;
        $model->payment_status = '未決済';
        $model->save();

        $request->session()->put('cart_add_flg','on');  #カートに追加したことをセッションに保存して、リダイレクト
        return redirect('/member/show?id='.$request->get('id'));
    }

    /**
     * 選択されたカートをカートリストから削除する。
     */
    public function cartDelete(CartListIdCheckRequest $request){
        ProductCartList::find($request->get('cartlist_id'))->delete();
        return redirect('/member/cart_index');
    }
}
