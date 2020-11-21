@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal u-margin--t-350 u-margin--l-100">
    <div class="c-operation-message u-margin--tb-70">
        商品のお届け先を指定してください。
    </div>


    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_address') }}">
        {{ csrf_field() }}
        <p class="c-delivery-destination__heading u-margin--tb-50">◎お届け先指定</p>
        <div class="form-group{{ $errors->has('address_select') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('address_select') ? ' has-error' : '' }}">
                <label>
                    <input class="u-margin--l-100 u-margin--tb-50" type="radio" name="address_select" value="登録済み住所" checked
                    @if(old('address_select')=='登録済み住所' ) checked @endif><a class="c-delivery-destination__text">登録されている住所へ配送する</a><br />
                </label>
                <label>
                    <input class="u-margin--l-100 u-margin--b-50" type="radio" name="address_select" value="個別指定住所"
                    @if(old('address_select')=='個別指定住所' ) checked @endif><a class="c-delivery-destination__text">配達先を指定する</a>
                </label>
                @if ($errors->has('address_select'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('address_select') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="c-delivery-destination u-margin--t-30 u-margin--l-200">
            <p class="c-delivery-destination__heading u-margin--tb-50">○個別指定・配達先情報</p>

            <div class="u-margin--l-100">
                <div class="form-group{{ $errors->has('receiver_name') ? ' has-error' : '' }}">
                    <label for="receiver_name" class="">受取人氏名等</label>

                    <div>
                        <a>　必須　</a>
                        <input type="text" class="c-delivery-destination__receiver_name u-margin--tb-50" name="receiver_name"
                            value={{old('receiver_name')}}>
                        @if ($errors->has('receiver_name'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('receiver_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('postal_code1') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('postal_code2') ? ' has-error' : '' }}">
                    <label for="postal_code">郵便番号（postal_code）</label>
                 <div>
                        <a>　必須　</a>
                        <input type="text" class="c-delivery-destination__postal_code1  u-margin--tb-50" name="postal_code1"
                            value={{old('postal_code1')}}>
                        -
                        <input type="text" class="c-delivery-destination__postal_code2 u-margin--tb-50" name="postal_code2"
                            value={{old('postal_code2')}}>
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
                    <label for="address1">都道府県</label>

                    <div>
                        <a>　表示　</a>
                        <input type="text" class="c-delivery-destination__address1 u-margin--tb-50" name="address1" readonly
                            value={{old('address1')}}>

                        @if ($errors->has('address1'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                    <label for="address2">市区町村</label>

                    <div>
                        <a>　表示　</a>
                        <input type="text" class="c-delivery-destination__address2 u-margin--tb-50" name="address2" readonly
                            value={{old('address2')}}>

                        @if ($errors->has('address2'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('address3') ? ' has-error' : '' }}">
                    <label for="address3">町域</label>

                    <div>
                        <a>　表示　</a>
                        <input type="text" class="c-delivery-destination__address3 u-margin--tb-50" name="address3" readonly
                            value={{old('address3')}}>

                        @if ($errors->has('address3'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address3') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('address4') ? ' has-error' : '' }}">
                    <label for="address4">それ以降の住所</label>

                    <div>
                        <a>　必須　</a>
                        <input type="text" class="c-delivery-destination__address4 u-margin--tb-50" name="address4"
                            value={{old('address4')}}>

                        @if ($errors->has('address4'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address4') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('address5') ? ' has-error' : '' }}">
                    <label for="address5">アパート・マンション名</label>

                    <div>
                        <a>　任意　</a>
                        <input type="text" class="c-delivery-destination__address5 u-margin--tb-50" name="address5"
                            value={{old('address5')}}>

                        @if ($errors->has('address5'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address5') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('address6') ? ' has-error' : '' }}">
                    <label for="address6">部屋番号</label>

                    <div>
                        <a>　任意　</a>
                        <input type="text" class="c-delivery-destination__address6 u-margin--tb-50" name="address6"
                            value={{old('address6')}}>

                        @if ($errors->has('address6'))
                        <span class="c-help-block">
                            <strong>{{ $errors->first('address6') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <p class="c-delivery-destination__heading u-margin--tb-50">◎連絡先指定</p>
        <div class="form-group{{ $errors->has('phone_select') ? ' has-error' : '' }}">
            <label>
                <input class="u-margin--l-100 u-margin--tb-50" type="radio" name="phone_select" value="登録済み電話番号" checked
                @if(old('phone_select')=='登録済み電話番号' ) checked @endif><a class="c-delivery-destination__text">登録されている電話番号を連絡先とする</a><br />
            </label>
            <label>
                <input class="u-margin--l-100 u-margin--b-50" type="radio" name="phone_select" value="個別指定電話番号"
                @if(old('phone_select')=='個別指定電話番号' ) checked @endif><a class="c-delivery-destination__text">連絡先電話番号を指定する</a>
            </label>
            @if ($errors->has('phone_select'))
            <span class="c-help-block">
                <strong>{{ $errors->first('phone_select') }}</strong>
            </span>
            @endif
        </div>

        <div class="c-delivery-destination u-margin--t-30 u-margin--l-200">
            <p class="c-delivery-destination__heading u-margin--tb-50">○個別指定・連絡先電話番号</p>
            <div class="u-margin--l-100">
                <div class="form-group{{ $errors->has('phone_number1') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('phone_number2') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('phone_number3') ? ' has-error' : '' }}">
                    <label for="phone_number1">電話番号</label>
                 <div>
                        <a>　必須　</a>
                        <input type="text" class="c-delivery-destination__phone_number1" name="phone_number1"
                            value={{old('phone_number1')}}>
                        -
                        <input type="text" class="c-delivery-destination__phone_number2" name="phone_number2"
                            value={{old('phone_number2')}}>
                        -
                        <input type="text" class="c-delivery-destination__phone_number3" name="phone_number3"
                            value={{old('phone_number3')}}>
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
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="c-button--type2-2 u-margin--t-50">
                配達日時指定へ
            </button>
        </div>
    </form>
    <div>
        <button class="c-button--type2-3 u-margin--t-50" type="button" onclick=history.back()>戻る</button>
    </div>
</div>
@endsection