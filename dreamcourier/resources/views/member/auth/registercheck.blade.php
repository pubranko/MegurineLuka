@extends('member.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　会員新規登録（確認）</div>
                <div class="mbr-message-box">
                    以下の内容で会員登録を行います。　問題がなければ、ログインに使用するパスワードを入力し、
                    「上記の内容で入会する」ボタンを押してください。
                </div>
                <div class="panel-body">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Ｅメールアドレス</label>

                            <div class="col-md-6">
                                <a>　{{ $email }}　</a>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--ここからカスタム-->
                            <label for="last_name" class="col-md-4 control-label">姓名</label>

                            <div class="col-md-6">
                                <a>　{{$last_name}}　</a>
                            </div>

                            <label for="first_name" class="col-md-4 control-label">名前</label>

                            <div class="col-md-6">
                                <a>　{{$first_name}}　</a>
                            </div>

                            <label for="last_name_kana" class="col-md-4 control-label">姓名（フリガナ）</label>

                            <div class="col-md-6">
                                <a>　{{$last_name_kana}}　</a>
                            </div>

                            <label for="first_name_kana" class="col-md-4 control-label">名前（フリガナ）</label>

                            <div class="col-md-6">
                                <a>　{{$first_name_kana}}　</a>
                            </div>

                            <label for="birthday" class="col-md-4 control-label">生年月日</label>

                            <div class="col-md-6">
                                <a> {{$birthday_era}} </a>
                                <a> {{$birthday_year}} </a>/
                                <a> {{$birthday_month}} </a>/
                                <a> {{$birthday_day}} </a>
                            </div>

                            <label for="sex" class="col-md-4 control-label">性別</label>

                            <div class="col-md-6">
                                <a> {{$sex}} </a>
                            </div>

                            <label for="postal_code" class="col-md-4 control-label">郵便番号</label>

                            <div class="col-md-6">
                                <a>　{{$postal_code1}}-{{$postal_code2}}　</a>
                            </div>

                            <label for="address1" class="col-md-4 control-label">都道府県</label>

                            <div class="col-md-6">
                                <a>　{{$address1}}　</a>
                            </div>

                            <label for="address2" class="col-md-4 control-label">市区町村</label>

                            <div class="col-md-6">
                                <a>　{{$address2}}　</a>
                            </div>

                            <label for="address3" class="col-md-4 control-label">町域</label>

                            <div class="col-md-6">
                                <a>　{{$address3}}　</a>
                            </div>

                            <label for="address4" class="col-md-4 control-label">それ以降の住所</label>

                            <div class="col-md-6">
                                <a>　{{$address4}}　</a>
                            </div>

                            <label for="address5" class="col-md-4 control-label">アパート・マンション名</label>

                            <div class="col-md-6">
                                <a>　{{$address5}}　</a>
                            </div>

                            <label for="address6" class="col-md-4 control-label">部屋番号</label>

                            <div class="col-md-6">
                                <a>　{{$address6}}　</a>
                            </div>

                            <label for="phone_number" class="col-md-4 control-label">電話番号</label>

                            <div class="col-md-6">
                                <a>　{{$phone_number1}} - {{$phone_number2}} - {{$phone_number3}}　</a>
                            </div>

                        <div class="mbr-message-box">
                            上記の内容で問題がなければ、ログインに使用するパスワードを入力し、
                            「上記の内容で入会する」ボタンを押してください。
                        </div>

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/member/register') }}">
                        {{ csrf_field() }}

                        <!-- emailのバリデート（存在チェック）のため、非表示のemailフィールドを含める -->
                        <input id="email" type="hidden" class="form-control" name="email" value="{{$email}}">

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">パスワード</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="password" type="password" class="form-control" name="password" value="">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-4 control-label">パスワード再入力</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" >

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    上記の内容で入会する
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" onclick=history.back()>戻る</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
