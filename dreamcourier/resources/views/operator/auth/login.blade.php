@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ログイン</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/operator/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <label for="operator_code" class="col-sm-4 col-form-label text-md-right">{{ __('オペレーターコード / Ｅメールアドレス') }}</label>

                            <div class="col-md-6">
                                <input id="operator_code" type="text" class="form-control{{ $errors->has('operator_code') ? ' is-invalid' : '' }}" name="operator_code" value="{{ old('operator_code') }}" required autofocus>

                                @if ($errors->has('operator_code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('operator_code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">パスワード</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 次回以降は自動ログインする（Remember Me）
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    ログイン
                                </button>

                                <a class="btn btn-link" href="{{ url('/operator/password/reset') }}">
                                    パスワードを忘れた？
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
