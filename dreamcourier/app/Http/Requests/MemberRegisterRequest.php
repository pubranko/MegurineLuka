<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'member/register'){
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
        #例
        #$data = $this->all();
        #if (isset($data['last_name']))
        #    $data['last_name'] = mb_convert_kana($data['last_name'], 'RNKS');
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
            //
            'email' => 'unique:members',
            'password' => 'required|min:6|same:password_confirmation',
        ];
    }

    /**
     * sometimesでバリデートしたい場合に使用する。
     * rules を評価する前の状態の Validator を受け取り、afterフックしてくれる。
     *
     * @return array
     */
    public function withValidator (){

    }

    /**
     * バリデータのエラーメッセージをカスタマイズする。
     *
     * @return array
     */
    public function messages(){
        return [
            'email.unique' => '既に登録されているアドレスです。',
            'password.required' => 'パスワードは必ず指定してください。',
            'password.min' => '6文字以上のパスワードを指定してください',
            'password.same' => 'パスワード、パスワード再入力の値が異なります。',
        ];
    }
}
