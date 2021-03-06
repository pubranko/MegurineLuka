@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal u-margin--t-350 u-margin--l-100">
    <div class="c-operation-message u-margin--tb-70">
        以下の会員登録に必要な情報を入力してください。
    </div>
    <div class="p-member-info u-margin--t-50 u-margin--l-100">
        <form class="" role="form" method="POST" action="{{ url('/member/register/check') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="">Ｅメールアドレス</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="email" class="p-member-info__email" name="email" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <!--ここからカスタム-->
            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                <label for="last_name" class="col-md-4 control-label">姓名</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__last_name" name="last_name" value={{old('last_name')}}>
                    @if ($errors->has('last_name'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                <label for="first_name" class="col-md-4 control-label">名前</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__first_name" name="first_name" value={{old('first_name')}}>
                    @if ($errors->has('first_name'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('last_name_kana') ? ' has-error' : '' }}">
                <label for="last_name_kana" class="col-md-4 control-label">姓名（フリガナ）</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__last_name_kana" name="last_name_kana" value={{old('last_name_kana')}}>
                    @if ($errors->has('last_name_kana'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('last_name_kana') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('first_name_kana') ? ' has-error' : '' }}">
                <label for="first_name_kana" class="col-md-4 control-label">名前（フリガナ）</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__first_name_kana" name="first_name_kana" value={{old('first_name_kana')}}>
                    @if ($errors->has('first_name_kana'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('first_name_kana') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('birthday_era') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('birthday_year') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('birthday_month') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('birthday_day') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('wk_birthday_ymd') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('wk_birthday_era_ymd') ? ' has-error' : '' }}">
                    <label for="birthday" class="col-md-4 control-label">元号/西暦(選択)</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <select name="birthday_era" class="c-button--type2-5" >
                        <option value="" @if(old('birthday_era')=='') selected  @endif>選択してください</option>
                        <option value="西暦" @if(old('birthday_era')=='西暦') selected  @endif>西暦</option>
                        <option value="令和" @if(old('birthday_era')=='令和') selected  @endif>令和</option>
                        <option value="平成" @if(old('birthday_era')=='平成') selected  @endif>平成</option>
                        <option value="昭和" @if(old('birthday_era')=='昭和') selected  @endif>昭和</option>
                        <option value="大正" @if(old('birthday_era')=='大正') selected  @endif>大正</option>
                        <option value="明治" @if(old('birthday_era')=='明治') selected  @endif>明治</option>
                    </select>
                    <input type="text" class="p-member-info__birthday_year" name="birthday_year" value={{old('birthday_year')}}>
                    /
                    <input type="text" class="p-member-info__birthday_month" name="birthday_month" value={{old('birthday_month')}}>
                    /
                    <input type="text" class="p-member-info__birthday_day" name="birthday_day" value={{old('birthday_day')}}>
                    @if ($errors->has('birthday_era'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('birthday_era') }}</strong>
                        </span>
                    @elseif($errors->has('birthday_year'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('birthday_year') }}</strong>
                        </span>
                    @elseif($errors->has('birthday_month'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('birthday_month') }}</strong>
                        </span>
                    @elseif($errors->has('birthday_day'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('birthday_day') }}</strong>
                        </span>
                    @elseif($errors->has('wk_birthday_ymd'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('wk_birthday_ymd') }}</strong>
                        </span>
                    @elseif($errors->has('wk_birthday_era_ymd'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('wk_birthday_era_ymd') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            </div>
            </div>
            </div>
            <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                <label for="sex" class="col-md-4 control-label">性別</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="radio" name="sex" value="男性" @if(old('sex')=='男性') checked @endif>男性
                    <input type="radio" name="sex" value="女性" @if(old('sex')=='女性') checked @endif>女性
                    @if ($errors->has('sex'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('sex') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('postal_code1') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('postal_code2') ? ' has-error' : '' }}">
                <label for="postal_code1" class="col-md-4 control-label">郵便番号</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__postal_code1" name="postal_code1" value={{old('postal_code1')}}>
                    -
                    <input type="text" class="p-member-info__postal_code2" name="postal_code2" value={{old('postal_code2')}}>
                    @if ($errors->has('postal_code1'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('postal_code1') }}</strong>
                        </span>
                    @elseif ($errors->has('postal_code2'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('postal_code2') }}</strong>
                        </span>
                    @endif
                    <input type="button" class="c-button--type2-4" value="郵便番号より住所を検索" onclick="f_address_get()">
                </div>
            </div>
            </div>
            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                <label for="address1" class="col-md-4 control-label">都道府県</label>
                <div class="u-margin--tb-50">
                    <a>　表示　</a>
                    <input type="text" class="p-member-info__address1" name="address1" readonly value={{old('address1')}}>
                    @if ($errors->has('address1'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                <label for="address2" class="col-md-4 control-label">市区町村</label>
                <div class="u-margin--tb-50">
                    <a>　表示　</a>
                    <input type="text" class="p-member-info__address2" name="address2" readonly value={{old('address2')}}>
                    @if ($errors->has('address2'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('address3') ? ' has-error' : '' }}">
                <label for="address3" class="col-md-4 control-label">町域</label>
                <div class="u-margin--tb-50">
                    <a>　表示　</a>
                    <input type="text" class="p-member-info__address3" name="address3" readonly value={{old('address3')}}>
                    @if ($errors->has('address3'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address3') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('address4') ? ' has-error' : '' }}">
                <label for="address4" class="col-md-4 control-label">それ以降の住所</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__address4" name="address4" value={{old('address4')}}>
                    @if ($errors->has('address4'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address4') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('address5') ? ' has-error' : '' }}">
                <label for="address5" class="col-md-4 control-label">アパート・マンション名</label>
                <div class="u-margin--tb-50">
                    <a>　任意　</a>
                    <input type="text" class="p-member-info__address5" name="address5" value={{old('address5')}}>
                    @if ($errors->has('address5'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address5') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('address6') ? ' has-error' : '' }}">
                <label for="address6" class="col-md-4 control-label">部屋番号</label>
                <div class="u-margin--tb-50">
                    <a>　任意　</a>
                    <input type="text" class="p-member-info__address6" name="address6" value={{old('address6')}}>
                    @if ($errors->has('address6'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address6') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('phone_number1') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('phone_number2') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('phone_number3') ? ' has-error' : '' }}">
                <label for="phone_number1" class="col-md-4 control-label">電話番号</label>
                <div class="u-margin--tb-50">
                    <a>　必須　</a>
                    <input type="text" class="p-member-info__phone_number1" name="phone_number1" value={{old('phone_number1')}}>
                    -
                    <input type="text" class="p-member-info__phone_number2" name="phone_number2" value={{old('phone_number2')}}>
                    -
                    <input type="text" class="p-member-info__phone_number3" name="phone_number3" value={{old('phone_number3')}}>
                    @if ($errors->has('phone_number1'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('phone_number1') }}</strong>
                        </span>
                    @elseif ($errors->has('phone_number2'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('phone_number2') }}</strong>
                        </span>
                    @elseif ($errors->has('phone_number3'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('phone_number3') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="c-button--type2-2">
                        新規会員登録（次へ）
                    </button>
                </div>
            </div>
        </form>
    </div>


</div>
@endsection
