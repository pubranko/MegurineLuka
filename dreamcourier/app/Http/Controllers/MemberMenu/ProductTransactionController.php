<?php

namespace App\Http\Controllers\MemberMenu;

use App\Http\Controllers\Controller;    #追加
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryAddressCheckRequest; #追加
use App\Http\Requests\DeliveryDatetimeCheckRequest; #追加
use App\ProductCartList;    #追加
use App\ProductStockList;             #追加
use Auth;   #追加


class ProductTransactionController extends Controller
{
    /**
     * カートリストの一覧を表示する。
     */
    public function cartLists(Request $request){

        $queries = ProductCartList::query();
        $queries->Where('member_code',Auth::user()->member_code);   #ログインしてるユーザーのカートリストを取得
        $cart_lists = $queries->paginate(15);

        $wk_products=[];  #初期化
        foreach( $cart_lists as $cart){
            $wk_product_master = $cart->productMaster;
            $wk_product['cartlist_id']=$cart->id;
            $wk_product['wk_product_thumbnail'] = str_replace("public","storage",$wk_product_master->product_thumbnail);  #サムネイルのパスをクライアント側用に加工
            $wk_product['product_code']=$wk_product_master->product_code;
            $wk_product['product_name']=$wk_product_master->product_name;
            $wk_product['product_price']=$wk_product_master->product_price;

            $stock_queries = ProductStockList::where('product_code',$wk_product_master->product_code)->first();
            if($wk_product_master->selling_discontinued_classification=="販売中止"){     #販売中止区分
                $wk_product['wk_product_stock_quantity_status'] = "販売中止";
            }elseif($stock_queries->product_stock_quantity  > 3){                     #商品在庫状況を追加
                $wk_product['wk_product_stock_quantity_status'] = "在庫あり";
            }elseif($stock_queries->product_stock_quantity  > 0){
                $wk_product['wk_product_stock_quantity_status'] = "在庫あとわずか！";
            }else{
                $wk_product['wk_product_stock_quantity_status'] = "在庫なし";
            }
            $wk_products[] = $wk_product;
        }

        $items = ['wk_products' => $wk_products,'cart_lists'=>$cart_lists];

        return view('member.cart_lists',$items);
    }

    /**
     * 配送先指定画面
     */
    public function deliveryAddress(Request $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));


        #return "test";
        return view('member.delivery_address');
    }

    /**
     * 配送先指定画面チェック
     */
    public function deliveryAddressCheck(DeliveryAddressCheckRequest $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));
        #return "test";
        #return view('member.delivery_address');
        return redirect('/member/delivery_datetime');
    }

    /**
     * 配達日時指定画面
     */
    public function deliveryDatetime(Request $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));
        #$a = $request->get('address1').$request->get('address4');
        #var_export($a);

        #return "test";
        return view('member.delivery_datetime');
    }

    /**
     * 配達日時指定画面チェック
     */
    public function deliveryDatetimeCheck(DeliveryDatetimeCheckRequest $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));
        #$a = $request->get('address1').$request->get('address4');
        #var_export($a);

        #return "test";
        return view('member.delivery_payment');
    }

    /**
     * 支払い方法指定画面
     */
    public function deliveryPayment(Request $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));
        #$a = $request->get('address1').$request->get('address4');
        #var_export($a);

        #return "test";
        return view('member.delivery_payment');
    }

    /**
     * 支払い方法指定画面チェック
     */
    public function deliveryPaymentCheck(Request $request){

        #$queries = ProductCartList::find($request->get('cartlist_id'));
        #$a = $request->get('address1').$request->get('address4');
        #var_export($a);

        return "test";
        #return view('member.delivery_address');
    }
}