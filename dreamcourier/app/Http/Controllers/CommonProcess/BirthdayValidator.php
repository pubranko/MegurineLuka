<?php

namespace App\Http\Controllers\CommonProcess;

use App\Member;
use Validator;
/**
 * バリデート用に加工した西暦の生年月日(wk_birthday_ymd)のリクエストを追加する。
 * 和暦入力の場合、さらに和暦の生年月日(wk_birthday_era_ymd)をリクエストに追加する。
 * 西暦での実日付チェック、各和暦での範囲チェックのバリデートを実行した結果をで返す。
 */
class BirthdayValidator
{
    public function birthdayCheck($request)
    {
        #バリデート用のルール
        $rule = ['wk_birthday_ymd'=>'required|date',];
        #バリデートのエラーメッセージ
        $messages = ['wk_birthday_ymd.date'=> '実在する日付で入力してください',];

        $data = $request->all();

        #生年月日(西暦)(wk_birthday_ymd)を生成。和暦入力時は、和暦用の生年月日(wk_birthday_era_ymd)も生成する。
        #また、和暦の場合、和暦の範囲チェック用のルールとエラー時のメッセージを配列に追加。
        if (isset($data['birthday_era']) && isset($data['birthday_year']) && isset($data['birthday_month']) && isset($data['birthday_day'])){
            switch($data['birthday_era']){
                case "西暦":
                    $data['wk_birthday_ymd'] = $data['birthday_year']."/".$data['birthday_month']."/".$data['birthday_day'];
                    break;
                case "令和":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+2018)."/".$data['birthday_month']."/".$data['birthday_day'];
                    $rule = array_merge($rule,['wk_birthday_era_ymd'=>'integer|min:10501']);
                    $messages = array_merge($messages,['wk_birthday_era_ymd.min' => '実在しない和暦です(令和1年5月1日〜)',]);
                    break;
                case "平成":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1988)."/".$data['birthday_month']."/".$data['birthday_day'];
                    $rule = array_merge($rule,['wk_birthday_era_ymd'=>'integer|between:10108,310430']);
                    $messages = array_merge($messages,['wk_birthday_era_ymd.between' => '実在しない和暦です(平成1年1月8日〜平成31年4月30日)',]);
                    break;
                case "昭和":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1925)."/".$data['birthday_month']."/".$data['birthday_day'];
                    $rule = array_merge($rule,['wk_birthday_era_ymd'=>'integer|between:11225,640107']);
                    $messages = array_merge($messages,['wk_birthday_era_ymd.between' => '実在しない和暦です(昭和1年12月25日〜昭和64年1月7日)',]);
                    break;
                case "大正":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1911)."/".$data['birthday_month']."/".$data['birthday_day'];
                    $rule = array_merge($rule,['wk_birthday_era_ymd'=>'integer|between:10730,151224']);
                    $messages = array_merge($messages,['wk_birthday_era_ymd.between' => '実在しない和暦です(大正1年7月30日〜昭和15年12月24日)',]);
                    break;
                case "明治":
                    $data['wk_birthday_ymd'] = ($data['birthday_year']+1867)."/".$data['birthday_month']."/".$data['birthday_day'];
                    $rule = array_merge($rule,['wk_birthday_era_ymd'=>'integer|between:10125,450729']);
                    $messages = array_merge($messages,['wk_birthday_era_ymd.between' => '実在しない和暦です(明治1年1月25日〜明治45年7月29日)',]);
                    break;
            }
            if($data['birthday_era']!=="西暦"){
                $data['wk_birthday_era_ymd'] =  intval($data['birthday_year'].
                                                        str_pad($data['birthday_month'], 2, "0", STR_PAD_LEFT).
                                                        str_pad($data['birthday_day'], 2, "0", STR_PAD_LEFT));
            }
        }
        $request->merge($data);
        return Validator::make($request->all(),$rule,$messages);
    }
}