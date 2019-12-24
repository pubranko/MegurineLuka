<?php

namespace App\Http\Controllers\MemberAuth;

use App\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;         #追加 Rule:inのため

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/member/home';     #たぶん、ここで登録後のリダイレクト先を指定できる。〜（結果）画面へ変更する？

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('member.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        #全半角コンバート、空白除去、、、どうするかあとで検討
        #$cnv_data = $data;
        #$cnv_data['last_name'] = mb_convert_kana($cnv_data['last_name'],'RN');  #RN:半角英字、半角数字を全角へ
        #項目関連チェック、テーブル関連チェックもあとで検討

        $rules = [
            #'name' => 'required|max:255',  #廃止
            #'member_codeは、入力項目ではないので不要
            'email' => 'required|email|max:255|unique:members',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required',
            'last_name' => 'required|string|max:30',
            'first_name' => 'required|string|max:30',
            'last_name_kana' => 'required|string|max:60',
            'first_name_kana' => 'required|string|max:60',
            #生年月日のバリデーションもあとで検討 'birthday' => 'required',
            'birthday_era' => ['required',Rule::in("西暦","令和","平成","昭和","大正","明治")],
            'birthday_year' => 'required|digits:4',
            'birthday_month' => 'required|between:1,12',
            'birthday_day' => 'required|between:1,31',
            'sex' => ['required',Rule::in("男性","女性")],
            'postal_code1' => 'required|digits:3',
            'postal_code2' => 'required|digits:4',
            'address1' => 'required',
            'address2' => 'required',
            'address3' => 'required',
            'address4' => 'required|string',
            'address5' => 'required|string',
            'address6' => 'required|string',
            'phone_number1' => 'required|digits_between:1,11',
            'phone_number2' => 'required|digits_between:1,4',
            'phone_number3' => 'required|digits:4',
            #'enrollment_datetime' => '',
            #'unsubscribe_reason' => '',
            #'status' => '',
            #'purchase_stop_division' => '',
            #'temporary_update_operator_code' => '',
            #'temporary_update_approval_operator_code' => '',
            #'remember_token' => '',
        ];

        $messages = [
            'email.required' => 'メールアドレスは必ず指定してください。',
            'email.email' => 'メールアドレスの形式ではありません。',
            'email.max' => 'メールアドレスの文字数が最大値を超えています。',
            'email.unique' => '既に登録されているアドレスです。',

        ];

        return Validator::make($data,$rules,$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Member
     */
    protected function create(array $data)
    {
        #membersテーブルのmember_codeの最大値＋１を取得

        $membar_code_max =  Member::max('member_code')+1;
        echo "echoで確認".$membar_code_max;

        #'birthday_era' => ['required',Rule::in("西暦","令和","平成","昭和","大正","明治")],
        #'birthday_year' => 'required|digits:4',
        #'birthday_month' => 'required|between:1,12',
        #'birthday_day' => 'required|between:1,31',

        if($data['birthday_era']=="西暦"){
            $birthday = $data['birthday_year']."/".$data['birthday_month']."/".$data['birthday_day'];
        }

        return Member::create([
            #'name' => $data['name'],
            'member_code' => $membar_code_max,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),

            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'last_name_kana' => $data['last_name_kana'],
            'first_name_kana' => $data['first_name_kana'],
            'birthday' => $birthday,
            'sex' => $data['sex'],
            'postal_code1' => $data['postal_code1'],
            'postal_code2' => $data['postal_code2'],
            'address1' => $data['address1'],
            'address2' => $data['address2'],
            'address3' => $data['address3'],
            'address4' => $data['address4'],
            'address5' => $data['address5'],
            'address6' => $data['address6'],
            'phone_number1' => $data['phone_number1'],
            'phone_number2' => $data['phone_number2'],
            'phone_number3' => $data['phone_number3'],
            'enrollment_datetime' => date('Y/m/d H:i*s',time()),
            #'unsubscribe_reason' => '',

            'status' => '正式',
            #'purchase_stop_division' => '',
            #'temporary_update_operator_code' => '',
            #'temporary_update_approval_operator_code' => '',
            #'remember_token' => '',
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('member.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('member');
    }
}
