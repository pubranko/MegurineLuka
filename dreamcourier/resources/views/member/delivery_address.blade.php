@extends('member.layout.auth')

@section('content')
@include('member.subviews.menu_bar')

<div class="mbr-message-box">
    商品のお届け先を指定してください。
</div>


    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_address') }}">
        {{ csrf_field() }}
        <p>◎お届け先指定</p>
        <div class="form-group{{ $errors->has('address_select') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('address_select') ? ' has-error' : '' }}">
                <input type="radio" name="address_select" value="登録済み住所" checked @if(old('address_select')=='登録済み住所') checked @endif>登録されている住所へ配送する<br/>
                <input type="radio" name="address_select" value="個別指定住所" @if(old('address_select')=='個別指定住所') checked @endif>配達先を指定する
                @if ($errors->has('address_select'))
                <span class="help-block">
                    <strong>{{ $errors->first('address_select') }}</strong>
                </span>
            @endif
            </div>
        </div>

        <div class="delivery-Individual-box">

            <p>○個別指定・配達先情報</p>

            <div class="form-group{{ $errors->has('receiver_name') ? ' has-error' : '' }}">
                <label for="receiver_name" class="col-md-4 control-label">受取人氏名等</label>

                <div class="col-md-6">
                    <a>　必須　</a>
                    <input id="receiver_name" type="text" class="form-control" name="receiver_name" value={{old('receiver_name')}}>
                    @if ($errors->has('receiver_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('receiver_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('postal_code1') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('postal_code2') ? ' has-error' : '' }}">
                <label for="postal_code" class="col-md-4 control-label">郵便番号（postal_code）</label>

                <div class="col-md-6">
                    <a>　必須　</a>
                    <input id="postal_code1" type="text" class="form-control" name="postal_code1" value={{old('postal_code1')}}>
                    -
                    <input id="postal_code2" type="text" class="form-control" name="postal_code2" value={{old('postal_code2')}}>
                    @if ($errors->has('postal_code1'))
                        <span class="help-block">
                            <strong>{{ $errors->first('postal_code1') }}</strong>
                        </span>
                    @elseif ($errors->has('postal_code2'))
                        <span class="help-block">
                            <strong>{{ $errors->first('postal_code2') }}</strong>
                        </span>
                    @endif
                    <input type="button" class="btn btn-primary" value="郵便番号より住所を検索" onclick="f_address_get()">
                </div>
            </div>
            </div>

            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                <label for="address1" class="col-md-4 control-label">都道府県</label>

                <div class="col-md-6">
                    <a>　表示　</a>
                    <input id="address1" type="text" class="form-control" name="address1" readonly value={{old('address1')}}>

                    @if ($errors->has('address1'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                <label for="address2" class="col-md-4 control-label">市区町村</label>

                <div class="col-md-6">
                    <a>　表示　</a>
                    <input id="address2" type="text" class="form-control" name="address2" readonly value={{old('address2')}}>

                    @if ($errors->has('address2'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('address3') ? ' has-error' : '' }}">
                <label for="address3" class="col-md-4 control-label">町域</label>

                <div class="col-md-6">
                    <a>　表示　</a>
                    <input id="address3" type="text" class="form-control" name="address3" readonly value={{old('address3')}}>

                    @if ($errors->has('address3'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address3') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('address4') ? ' has-error' : '' }}">
                <label for="address4" class="col-md-4 control-label">それ以降の住所</label>

                <div class="col-md-6">
                    <a>　必須　</a>
                    <input id="address4" type="text" class="form-control" name="address4" value={{old('address4')}}>

                    @if ($errors->has('address4'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address4') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('address5') ? ' has-error' : '' }}">
                <label for="address5" class="col-md-4 control-label">アパート・マンション名</label>

                <div class="col-md-6">
                    <a>　任意　</a>
                    <input id="address5" type="text" class="form-control" name="address5" value={{old('address5')}}>

                    @if ($errors->has('address5'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address5') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('address6') ? ' has-error' : '' }}">
                <label for="address6" class="col-md-4 control-label">部屋番号</label>

                <div class="col-md-6">
                    <a>　任意　</a>
                    <input id="address6" type="text" class="form-control" name="address6" value={{old('address6')}}>

                    @if ($errors->has('address6'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address6') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

        </div>

        <p>◎連絡先指定</p>
        <div class="form-group{{ $errors->has('phone_select') ? ' has-error' : '' }}">
            <input type="radio" name="phone_select" value="登録済み電話番号" checked @if(old('phone_select')=='登録済み電話番号') checked @endif>登録されている電話番号を連絡先とする<br/>
            <input type="radio" name="phone_select" value="個別指定電話番号" @if(old('phone_select')=='個別指定電話番号') checked @endif>連絡先電話番号を指定する
            @if ($errors->has('phone_select'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone_select') }}</strong>
                </span>
            @endif
        </div>

        <div class="delivery-Individual-box">
            <p>○個別指定・連絡先電話番号</p>

            <div class="form-group{{ $errors->has('phone_number1') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('phone_number2') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('phone_number3') ? ' has-error' : '' }}">
                <label for="phone_number1" class="col-md-4 control-label">電話番号</label>

                <div class="col-md-6">
                    <a>　必須　</a>
                    <input id="phone_number1" type="text" class="form-control" name="phone_number1" value={{old('phone_number1')}}>
                    -
                    <input id="phone_number2" type="text" class="form-control" name="phone_number2" value={{old('phone_number2')}}>
                    -
                    <input id="phone_number3" type="text" class="form-control" name="phone_number3" value={{old('phone_number3')}}>

                    @if ($errors->has('phone_number1'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone_number1') }}</strong>
                        </span>
                    @elseif ($errors->has('phone_number2'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone_number2') }}</strong>
                        </span>
                    @elseif ($errors->has('phone_number3'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone_number3') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="delivery-button">
                配達日時指定へ
            </button>
        </div>
    </form>
    <div class="col-md-6">
        <button class="delivery-button" type="button" onclick=history.back()>戻る</button>
    </div>

@endsection
