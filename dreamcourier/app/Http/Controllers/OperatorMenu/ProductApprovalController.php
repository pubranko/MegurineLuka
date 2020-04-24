<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductMaster;                         #追加
use App\Http\Requests\ProductApprovalRequest; #追加


class ProductApprovalController extends Controller
{
    /**
     * 
     */
    public function approval(ProductApprovalRequest $request){

        $select_id = $request->get('select_id');    //チェックされた配列を取得

        if(isset($select_id)){                      //一件以上選択されている場合、選択されたレコードのステータスを正式へ変更
            foreach($select_id as $id){
                #承認対象の商品情報マスタ（product_masters）取得→ステータスが正式以外の場合は値を編集→保存
                $model = ProductMaster::lockForUpdate()->find($id);
                if($model->status !== '正式'){
                    $model->status = '正式';
                    $model->temporary_update_approver_operator_code = $request->user()->operator_code;
                    $model->save();
                }
            }
        }

        #セッションにリクエストを保存
        $request->session()->put('product_approval_request',$request->all());

        return redirect('/operator/product/search');
    }

}
