<?php

namespace App\Http\Controllers\MemberMenu;

use App\Http\Controllers\Controller;    #追加
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryAddressCheckRequest; #追加
use App\Http\Requests\DeliveryDatetimeCheckRequest; #追加
use App\Http\Requests\DeliveryPaymentCheckRequest; #追加
use App\ProductCartList;            #追加
use App\ProductStockList;           #追加
use App\Member;                     #追加
use App\ProductTransactionList;     #追加
use App\ProductDeliveryStatusList;  #追加
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
        $request->session()->put('cartLists',$request->all());
        return view('member.delivery_address');
    }

    /**
     * 配送先指定画面チェック
     */
    public function deliveryAddressCheck(DeliveryAddressCheckRequest $request){
        $request->session()->put('deliveryAddress',$request->all());
        return redirect('/member/delivery_datetime');
    }

    /**
     * 配達日時指定画面
     */
    public function deliveryDatetime(Request $request){
        return view('member.delivery_datetime');
    }

    /**
     * 配達日時指定画面チェック
     */
    public function deliveryDatetimeCheck(DeliveryDatetimeCheckRequest $request){
        $request->session()->put('deliveryDatetime',$request->all());
        return redirect('/member/delivery_payment');
    }

    /**
     * 支払い方法指定画面
     */
    public function deliveryPayment(Request $request){

        $y = date("y",time());      #現在の年（西暦下２桁）
        $years = range($y,$y+6);    #現在の年から７年分の配列を作成

        return view('member.delivery_payment',['years' => $years]);
    }

    /**
     * 支払い方法指定画面チェック
     * 購入手続きカート一覧、配達先指定、配達日時指定、支払い方法指定画面の入力内容を購入手続き（確認）画面へ表示する。
     */
    public function deliveryPaymentCheck(DeliveryPaymentCheckRequest $request){
        ############################################################################################
        #購入手続きの前画面（カート一覧、配達先指定、配達日時指定）の情報をセッションより取得する
        ############################################################################################
        $data['cartLists'] = $request->session()->get('cartLists');
        $data['deliveryAddress'] = $request->session()->get('deliveryAddress');
        $data['deliveryDatetime'] = $request->session()->get('deliveryDatetime');

        ############################################################################################
        #カート一覧で選択されたカートリストidより、商品情報を取得する。
        #また、viewに渡すデータへ加工する。
        ############################################################################################
        $wk_product_master = ProductCartList::find($data['cartLists']['cartlist_id'])->productMaster;
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

        ############################################################################################
        #ログイン中のメンバー情報を取得する。
        #また、viewに渡すデータへ加工する。
        ############################################################################################
        $member_query = Member::query();
        $member_query->where('member_code',Auth::user()->member_code);
        $member_query->where('status',"正式");
        $member = $member_query->first();

        $wk_delivery_destination['address_select'] = $data['deliveryAddress']['address_select'];
        $wk_delivery_destination['phone_select'] = $data['deliveryAddress']['phone_select'];

        #登録済み住所へ配達する場合は、会員情報マスタの内容を設定する。
        #個別指定住所へ配達する場合、画面より入力された情報を設定する。
        if($data['deliveryAddress']['address_select']=="登録済み住所"){
            $wk_delivery_destination['receiver_name'] = $member->last_name." ".$member->first_name;
            $wk_delivery_destination['postal_code1'] = str_pad($member->postal_code1, 3, "0", STR_PAD_LEFT);
            $wk_delivery_destination['postal_code2'] = str_pad($member->postal_code2, 4, "0", STR_PAD_LEFT);
            $wk_delivery_destination['address1'] = $member->address1;
            $wk_delivery_destination['address2'] = $member->address2;
            $wk_delivery_destination['address3'] = $member->address3;
            $wk_delivery_destination['address4'] = $member->address4;
            $wk_delivery_destination['address5'] = $member->address5;
            $wk_delivery_destination['address6'] = $member->address6;
        }else{
            $wk_delivery_destination['receiver_name'] = $data['deliveryAddress']['receiver_name'];
            $wk_delivery_destination['postal_code1'] = $data['deliveryAddress']['postal_code1'];
            $wk_delivery_destination['postal_code2'] = $data['deliveryAddress']['postal_code2'];
            $wk_delivery_destination['address1'] = $data['deliveryAddress']['address1'];
            $wk_delivery_destination['address2'] = $data['deliveryAddress']['address2'];
            $wk_delivery_destination['address3'] = $data['deliveryAddress']['address3'];
            $wk_delivery_destination['address4'] = $data['deliveryAddress']['address4'];
            $wk_delivery_destination['address5'] = $data['deliveryAddress']['address5'];
            $wk_delivery_destination['address6'] = $data['deliveryAddress']['address6'];
        }

        #連絡先が、登録済み電話番号は、会員情報マスタの内容を設定する。
        #個別指定電話番号の場合、画面より入力された情報を設定する。
        if($data['deliveryAddress']['phone_select']=="登録済み電話番号"){
            $wk_delivery_destination['phone_number1'] = $member->phone_number1;
            $wk_delivery_destination['phone_number2'] = $member->phone_number2;
            $wk_delivery_destination['phone_number3'] = $member->phone_number3;
        }else{
            $wk_delivery_destination['phone_number1'] = $data['deliveryAddress']['phone_number1'];
            $wk_delivery_destination['phone_number2'] = $data['deliveryAddress']['phone_number2'];
            $wk_delivery_destination['phone_number3'] = $data['deliveryAddress']['phone_number3'];
        }

        ############################################################################################
        #画面入力された配達日時を、viewに渡すデータへ加工する。
        ############################################################################################
        $date= strtotime($data['deliveryDatetime']['delivery_date']);

        $wk_datetime['delivery_date_edit'] = date('Y 年 m 月 d 日',$date);
        $wk_datetime['delivery_date'] =$data['deliveryDatetime']['delivery_date'];
        $wk_datetime['delivery_time'] =$data['deliveryDatetime']['delivery_time'];

        ############################################################################################
        #支払いを行うクレジットカード情報を取得する。（！！！未実装！！！　フェーズ２以降で開発予定）
        #また、viewに渡すデータへ加工する。
        ############################################################################################
        $wk_credit_card['payment_select'] =$request->get('payment_select');
        #登録済みクレジットカードで支払いを行う場合は、支払い方法登録マスタより情報を取得する。（！！！未実装！！！　フェーズ２以降で開発予定）
        #個別指定クレジットカードで支払いを行う場合は、画面より入力された情報を設定する。
        if($request->get('payment_select')== '登録済みクレジットカード'){
            $wk_credit_card['card_number'] = '9999-9999-9999-9999';         #テスト用の暫定値を編集
            $wk_credit_card['card_month'] = '12';                           #テスト用の暫定値を編集
            $wk_credit_card['card_year'] = '99';                            #テスト用の暫定値を編集
            $wk_credit_card['card_name'] = 'TEST tester';                   #テスト用の暫定値を編集
            $wk_credit_card['card_security_code'] = '999';                  #テスト用の暫定値を編集
        }else{
            $wk_credit_card['card_number'] = $request->get('card_number');
            $wk_credit_card['card_month'] = $request->get('card_month');
            $wk_credit_card['card_year'] = $request->get('card_year');
            $wk_credit_card['card_name'] = $request->get('card_name');
            $wk_credit_card['card_security_code'] = $request->get('card_security_code');
        }

        $items = ['wk_product'=>$wk_product,'wk_delivery_destination'=>$wk_delivery_destination,'wk_datetime'=>$wk_datetime,'wk_credit_card'=>$wk_credit_card];

        $request->session()->put('items',$items);
        return redirect('/member/delivery_check');
    }

    /**
     * 購入手続き（確認）画面を表示する。
     */
    public function deliveryCheck(Request $request){
        #$data['items'] = $request->session()->get('items');
        return view('/member/delivery_check',$request->session()->get('items'));
    }

    /**
     * 1.商品取引リスト(product_transaction_lists)、商品配送状況リスト(product_delivery_status_lists)へ登録する。
     * 2.商品カートリスト(product_cart_lists)を決済済みにする。(未着！！！！！！！！！！！！！)
     * 3.商品在庫リスト(product_stock_lists)より在庫を削減する。(未着！！！！！！！！！！！！！)
     * 4.購入手続き（結果）画面を表示する。
     */
    public function deliveryRegister(Request $request){
        #購入手続きの前画面（カート一覧、配達先指定、配達日時指定、クレジットカード指定）の情報をセッションより取得する
        $data['cartLists'] = $request->session()->get('cartLists');
        $data['items'] = $request->session()->get('items');
        $wk_product = $data['items']['wk_product'];
        $wk_delivery_destination = $data['items']['wk_delivery_destination'];
        $wk_datetime = $data['items']['wk_datetime'];
        $wk_credit_card = $data['items']['wk_credit_card'];

        #テーブルロック
        ProductTransactionList::lockForUpdate()->get();

        $transaction_model = new ProductTransactionList;
        $transaction_number = ProductTransactionList::max('transaction_number')+1;   #お取引番号：最大値＋１
        $transaction_model->transaction_number = $transaction_number;
        $transaction_model->member_code = Auth::user()->member_code;
        $transaction_model->product_code = $wk_product['product_code'];
        $transaction_model->product_name = $wk_product['product_name'];
        $transaction_model->product_price = $wk_product['product_price'];
        $transaction_model->receiver_name = $wk_delivery_destination['receiver_name'];
        $transaction_model->postal_code1 = $wk_delivery_destination['postal_code1'];
        $transaction_model->postal_code2 = $wk_delivery_destination['postal_code2'];
        $transaction_model->address1 = $wk_delivery_destination['address1'];
        $transaction_model->address2 = $wk_delivery_destination['address2'];
        $transaction_model->address3 = $wk_delivery_destination['address3'];
        $transaction_model->address4 = $wk_delivery_destination['address4'];
        $transaction_model->address5 = $wk_delivery_destination['address5'];
        $transaction_model->address6 = $wk_delivery_destination['address6'];
        $transaction_model->phone_number1 = $wk_delivery_destination['phone_number1'];
        $transaction_model->phone_number2 = $wk_delivery_destination['phone_number2'];
        $transaction_model->phone_number3 = $wk_delivery_destination['phone_number3'];
        $transaction_model->delivery_date = $wk_datetime['delivery_date'];
        $transaction_model->delivery_time = $wk_datetime['delivery_time'];
        $transaction_model->card_number = $wk_credit_card['card_number'];
        $transaction_model->billing_status = '未請求';
        $transaction_model->deposit_appropriation = 0;
        $transaction_model->status = '正式';
        $transaction_model->transaction_status = '';
        $transaction_model->temporary_updater_operator_code = '';
        $transaction_model->temporary_update_approver_operator_code = '';

        $delivery_status_model = new ProductDeliveryStatusList;
        $delivery_status_model->transaction_number = $transaction_model->transaction_number;
        $delivery_status_model->delivery_status = '配達準備中';
        $delivery_status_model->delivery_status_update_at = now();
        $delivery_status_model->delivery_memo = '';
        $delivery_status_model->status = '正式';
        $delivery_status_model->invalid_flg = false;
        $delivery_status_model->temporary_updater_operator_code = '';
        $delivery_status_model->temporary_update_approver_operator_code = '';

        $transaction_model->save();
        $delivery_status_model->save();

        $data['items']['transaction_number'] = $transaction_number;
        #テーブル登録後の後処理
        #二重送信対策(セッションの再作成)
        $request->session()->regenerateToken();
        #return redirect('/member/delivery_result');
        return view('member.delivery_result',$data['items']);
    }

}