@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal u-mt-350 u-ml-100">
    <div class="c-operation-message u-mtb-70">
        商品の支払い方法を指定してください。
    </div>

    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_payment') }}">
        {{ csrf_field() }}

        <p>◎支払い方法</p>
        <div class="form-group{{ $errors->has('payment_select') ? ' has-error' : '' }}">
            <input type="radio" name="payment_select" value="登録済みクレジットカード" checked @if(old('payment_select')=='登録済みクレジットカード') checked @endif>登録済みクレジットカード<br/>
            <input type="radio" name="payment_select" value="個別指定クレジットカード" @if(old('payment_select')=='個別指定クレジットカード') checked @endif>個別指定・クレジットカード情報入力
            @if ($errors->has('payment_select'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('payment_select') }}</strong>
                </span>
            @endif
        </div>

        <div class="c-delivery-destination u-mt-30 u-ml-100">
            <p class="c-delivery-destination__heading u-mtb-50">◎個別指定・クレジットカード</p>

            <div class="u-ml-100">
                <div class="form-group{{ $errors->has('card_number') ? ' has-error' : '' }}">
                    <label for="card_number" class="col-md-4 control-label">カード番号　</label>
                    <div class="col-md-6">
                        <a>　必須　</a>
                        <input type="text" class="c-delivery-destination__card_number" name="card_number" value={{old('card_number')}}>

                        @if ($errors->has('card_number'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('card_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('card_month') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('card_year') ? ' has-error' : '' }}">
                    <label for="card_month" class="col-md-4 control-label">カード有効期限</label>
                    <div class="col-md-6">
                        <a>　必須　</a>
                        <select name="card_month">
                            <option value='' @if(old('card_month')=='') selected  @endif></option>
                            <option value='01' @if(old('card_month')=='01') selected  @endif>01</option>
                            <option value='02' @if(old('card_month')=='02') selected  @endif>02</option>
                            <option value='03' @if(old('card_month')=='03') selected  @endif>03</option>
                            <option value='04' @if(old('card_month')=='04') selected  @endif>04</option>
                            <option value='05' @if(old('card_month')=='05') selected  @endif>05</option>
                            <option value='06' @if(old('card_month')=='06') selected  @endif>06</option>
                            <option value='07' @if(old('card_month')=='07') selected  @endif>07</option>
                            <option value='08' @if(old('card_month')=='08') selected  @endif>08</option>
                            <option value='09' @if(old('card_month')=='09') selected  @endif>09</option>
                            <option value='10' @if(old('card_month')=='10') selected  @endif>10</option>
                            <option value='11' @if(old('card_month')=='11') selected  @endif>11</option>
                            <option value='12' @if(old('card_month')=='12') selected  @endif>12</option>
                        </select>
                        <a>/</a>
                        <select name="card_year">
                            <option value='' @if(old('card_year')=='') selected  @endif></option>
                            @foreach($years as $year)
                                <option value='{{$year}}' @if(old('card_year')==$year) selected  @endif>{{$year}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('card_month'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('card_month') }}</strong>
                            </span>
                        @elseif($errors->has('card_year'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('card_year') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                </div>

                <div class="form-group{{ $errors->has('card_name') ? ' has-error' : '' }}">
                    <label for="card_name" class="col-md-4 control-label">カード名義人</label>

                    <div class="col-md-6">
                        <a>　必須　</a>
                        <input type="text" name="card_name" value="{{old('card_name')}}">
                        @if ($errors->has('card_name'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('card_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('card_security_code') ? ' has-error' : '' }}">
                    <label for="card_security_code" class="col-md-4 control-label">セキュリティコード</label>
                    <div class="col-md-6">
                        <a>　必須　</a>
                        <input type="text" name="card_security_code" value="{{old('card_security_code')}}">
                        @if ($errors->has('card_security_code'))
                            <span class="c-help-block">
                                <strong>{{ $errors->first('card_security_code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="c-button-type1-4 u-mt-50">
                確認画面へ
            </button>
        </div>
    </form>
    <div class="col-md-6">
        <button class="c-button-type1-1 u-mt-50" type="button" onclick=history.back()>戻る</button>
    </div>
</div>
@endsection
