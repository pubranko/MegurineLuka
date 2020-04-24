<?php

namespace App\Http\Controllers\MemberAuth;

use App\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;  #追加
use App\Http\Requests\MemberRegisterCheckRequest; #追加
use App\Http\Requests\MemberRegisterRequest; #追加
use App\Http\Controllers\CommonProcess\BirthdayValidator; #追加

use Carbon\Carbon;  #追加

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
     * membersテーブルのモデルインスタンスを生成
     * @param  array  $data
     * @return Member
     */
    protected function create(array $data)
    {
        #membersテーブルのmember_codeの最大値＋１を取得
        $membar_code_max =  Member::max('member_code')+1;

        return Member::create([
            'member_code' => $membar_code_max,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),        #パスワードはハッシュ値で保存
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'last_name_kana' => $data['last_name_kana'],
            'first_name_kana' => $data['first_name_kana'],
            'birthday' => $data['wk_birthday_ymd'],         #ミドルウェアで付与したwk_birthday_ymdを使用(西暦に統一した値　例:2019/5/1)
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
            'enrollment_datetime' => date('Y/m/d H:i*s',Carbon::now()->timestamp),
            'status' => '正式',
        ]);
    }

    /**
     * 新規会員登録（入力）画面
     *
     * @return \Illuminate\Http\Response
     */
    public function registerIn()
    {
        return view('member.auth.registerin');
    }

    /**
     * 新規会員登録（確認）画面
     * 新規会員登録（入力）の入力後、内容の確認とログインパスワードの入力を行う画面を呼び出す。
     */
    public function registerCheck(MemberRegisterCheckRequest $request)  #カスタムしたフォームリクエスト使用
    {
        $birthday = new BirthdayValidator;
        $validator = $birthday->birthdayCheck($request);
        if($validator->fails()){
            return redirect('/member/register/in')->withErrors($validator)->withInput();
        }else{
            $request->session()->put('register_in_request',$request->all());    #セッションにリクエストを保存
            #return view('member.auth.registercheck',$request->all());           #前画面の入力内容を引き渡して次の画面へ
            return redirect('/member/register/checkview');
        }
    }

    /**
     * 新規会員登録（確認）画面を表示する。
     */
    public function registerCheckView(Request $request){
        $data = $request->session()->get('register_in_request');    #新規会員登録（入力）の入力情報をセッションより取得
        return view('member.auth.registercheck',$data);   #前画面の入力内容を引き渡して次の画面へ
    }

    /**
     * 新規会員登録（確認）後の会員登録処理
     * 新規会員登録（確認）画面へ遷移
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Illuminate\Foundation\Auth\RegistersUsersをオーバーライドしカスタマイズ
     */
    public function register(MemberRegisterRequest $request)  #カスタムしたフォームリクエスト使用
    {
        #新規会員登録（入力）＋新規会員登録（確認）のリクエストをマージ
        $register_in_request = $request->session()->get('register_in_request');
        $request_merge = array_merge($request->all(),$register_in_request);
        #membersのモデルインスタンス生成。ユーザー登録のLaravelシステムイベント発行
        event(new Registered($user = $this->create($request_merge)));

        $this->guard()->login($user);   #登録時にログインする
        $request->session()->regenerateToken(); #二重送信対策

        return view('member.auth.registerresult');  #新規会員登録（結果）画面へ
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
