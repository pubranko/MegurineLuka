<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddressMaster;          #追加

class AddressMastersController extends Controller
{
    /**
     * 受け取った郵便番号より住所を検索しかえす。
     * 新規会員登録（入力）の入力後、内容の確認とログインパスワードの入力を行う画面を呼び出す。
     */
    public function postCodeSearch(Request $request)
    {
        $postcode1 = $request->get('postal_code1');
        $postcode2 = $request->get('postal_code2');

        $q = AddressMaster::query();
        $address = $q->where('zip',$postcode1.'-'.$postcode2)->first();
        if($address){
            $response = '{"result_flg":"有り",'.
                        '"ken_name":"'.$address["ken_name"].
                        '","city_name":"'.$address["city_name"].
                        '","town_name":"'.$address["town_name"].'"}';

        }else{
            $response = '{"result_flg":"無し"}';

        }
        return $response;
    }
}
