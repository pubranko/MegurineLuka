@extends('member.layout.auth')

@section('content')
<div class="container l-body-nomal u-margin--t-350 u-margin--l-100">
    <div class="c-operation-message u-margin--tb-70">
        以下の内容で会員登録を行います。　問題がなければ、ログインに使用するパスワードを入力し、
        「上記の内容で入会する」ボタンを押してください。
    </div>
    <p class="p-member-info__heading">◎入力情報確認</p>
    <div class="u-margin--l-100">
        <table class="c-table-type1 u-margin--t-20">
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">Ｅメールアドレス</th>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    @if ($errors->has('email'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <td class="c-table-type1__cell">{{ $email }}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">姓名</th>
                <td class="c-table-type1__cell">{{$last_name}}　{{$first_name}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">姓名（カナ）</th>
                <td class="c-table-type1__cell">{{$last_name_kana}}　{{$first_name_kana}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">郵便番号</th>
                <td class="c-table-type1__cell">{{$postal_code1}}-{{$postal_code2}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">住所</th>
                <td class="c-table-type1__cell">
                    {{$address1}}
                    {{$address2}}
                    {{$address3}}
                    {{$address4}}<a>　</a>
                    {{$address5}}<a>　</a>
                    {{$address6}}
                </td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">連絡先電話番号</th>
                <td class="c-table-type1__cell">{{$phone_number1}} - {{$phone_number2}} - {{$phone_number3}}</td>
            </tr>
        </table>
    </div>

    <div>
        <p class="p-member-info__heading">◎パスワード入力</p>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/member/register') }}">
            {{ csrf_field() }}
            <div class="p-member-info u-margin--l-100 u-margin--t-50">
                <!-- emailのバリデート（存在チェック）のため、非表示のemailフィールドを含める -->
                <input id="email" type="hidden" class="form-control" name="email" value="{{$email}}">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="">パスワード</label>
                    <div class="col-md-6">
                        <a>　必須　</a>
                        <input id="password" type="password" class="p-member-info__password" name="password" value="">
                        @if ($errors->has('password'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password_confirmation" class="">パスワード再入力</label>
                    <div class="col-md-6">
                        <a>　必須　</a>
                        <input id="password_confirmation" type="password" class="p-member-info__password" name="password_confirmation" value="" >
                        @if ($errors->has('password_confirmation'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                    <button type="submit" class="c-button--type2-1 u-margin--tb-100">
                        上記の内容で入会する
                    </button>
                    <button type="button" class="c-button--type2-3" onclick=history.back()>戻る</button>
            </div>
        </form>
    </div>
</div>
@endsection
