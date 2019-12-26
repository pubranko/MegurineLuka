<?php

namespace App\Http\Controllers\MemberAuth;

use App\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;         #追加 Rule:inのため
use Illuminate\Http\Request;            #追加 
use Illuminate\Auth\Events\Registered;  #追加


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
     * 新規会員登録（入力）画面のバリデータ
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function in_validator(array $data)
    {
        #全半角コンバート、空白除去、、、どうするかあとで検討
        #$cnv_data = $data;
        #$cnv_data['last_name'] = mb_convert_kana($cnv_data['last_name'],'RN');  #RN:半角英字、半角数字を全角へ
        #項目関連チェック、テーブル関連チェックもあとで検討

        $rules = [
            'email' => 'required|email|max:255|unique:members',
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
            'phone_number1' => 'required|digits_between:1,11',
            'phone_number2' => 'required|digits_between:1,4',
            'phone_number3' => 'required|digits:4',
        ];

        $messages = [
            'email.required' => 'メールアドレスは必ず指定してください。',
            'email.email' => 'メールアドレスの形式ではありません。',
            'email.max' => 'メールアドレスの文字数が最大値を超えています。',
            'email.unique' => '既に登録されているアドレスです。',
            #まだまだ足りない。あとで追加
        ];

        return Validator::make($data,$rules,$messages);
    }

    /**
     * 新規会員登録（確認）画面のバリデータ
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function check_validator(array $data)
    {
        $rules = [
            'email' => 'unique:members',
            'password' => 'required|min:6|same:password_confirmation',
        ];

        $messages = [
            'email.unique' => '既に登録されているアドレスです。',
            'password.required' => 'パスワードは必ず指定してください。',
            'password.min' => '6文字以上のパスワードを指定してください',
            'password.same' => 'パスワード、パスワード再入力の値が異なります。',
        ];

        return Validator::make($data,$rules,$messages);
    }

    /**
     * membersテーブルのモデルインスタンスを生成
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
            'status' => '正式',
        ]);
    }

    /**
     * 新規会員登録（入力）画面
     *
     * @return \Illuminate\Http\Response
     */
    public function registrationInForm()
    {
        return view('member.auth.registerin');
    }

    /**
     * 新規会員登録（確認）画面
     * 新規会員登録（入力）の入力後、内容の確認とログインパスワードの入力を行う画面を呼び出す。
     */
    public function registrationCheckForm(Request $request)
    {
        $this->in_validator($request->all())->validate();                   #前画面の入力内容をバリデート
        #echo "var_exportで確認";
        #var_dump($request->all());
        $request->session()->put('register_in_request',$request->all());    #セッションにリクエストを保存
        $item = $request->all();                                            #前画面の入力内容を$itemへ
        return view('member.auth.registercheck',$item);
    }

    /**
     * 新規会員登録（確認）後の会員登録処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Illuminate\Foundation\Auth\RegistersUsersをオーバーライドしカスタマイズ
     */
    public function register(Request $request)
    {

        $prev_scr_request = $request->session()->get('register_in_request');    #前画面のリクエストをセッションより取得
        $merge_request = array_merge($prev_scr_request,$request->all());        #メールアドレスとパスワードのバリデートのため配列をマージ。
        $this->check_validator($merge_request)->validate();                     #前画面の入力内容＆メールアドレスのバリデート

        #新規会員登録（入力）と新規会員登録（確認）の入力内容よりmembersのモデルインスタンス生成
        #ユーザー登録のLaravelシステムイベント発行
        event(new Registered($user = $this->create($merge_request)));

        $this->guard()->login($user);   #登録時にログインする

        // 二重送信対策
        $request->session()->regenerateToken();

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * 会員登録後、登録結果のメッセージを表示する画面を呼び出す。
     */
    public function registrationResultForm()
    {
        return view('member.auth.registerresult');
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
