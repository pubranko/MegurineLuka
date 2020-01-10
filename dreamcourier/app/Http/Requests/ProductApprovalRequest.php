<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ProductMasters;                         #追加
use Illuminate\Validation\Rule;         #追加 Rule:inのため

class ProductApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'operator/product/approval'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * バリデーションの前処理（オーバーライド）。
     * 必要に応じて使用する予定。
     * @return array
     */
    public function validationData()
    {
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'select_id' => 'required',
        ];
    }
    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator ($validator){

        #ここで商品マスタを参照して、仮登録したオペレーターコードを取得
        $select_id = $this->get('select_id');    //チェックされた配列を取得
        if(isset($select_id)){                      //一件以上選択されている場合、選択されたレコードのステータスを正式へ変更
            foreach($select_id as $id){
                #商品情報マスタ（product_masters）より該当のIDのレコードを取得し、仮更新者_操作者コードを取得する。
                #今回操作しているオペレーターと、仮登録・仮変更を行ったオペレーターが同一の場合エラー
                $model = ProductMasters::find($id);
                $temporary_updater_operator_code = $model->temporary_updater_operator_code;
                $validator->sometimes('approval_operator_code',[Rule::notIn($temporary_updater_operator_code)],function($input){
                    return isset($input->select_id);
                });
            }
        }
    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'select_id.required' => '承認対象の選択が漏れています。',
            'approval_operator_code.not_in' => '自身で仮登録・仮変更したものは承認できません。',
        ];
    }
}
