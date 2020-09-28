@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal u-mt-350 u-ml-100">
    <div class="c-operation-message u-mtb-70">
        <ul>
            <li>商品の配達日時を指定してください。</li>
            <li>最短お届け時間は、現時点より12時間以降となります。</li>
            <li>同じ時間帯に複数の商品のお届けはできません。</li>
        </ul>
    </div>

    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_datetime') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('delivery_date') ? ' has-error' : '' }}">
        <div class="form-group{{ $errors->has('wk_delivery_datetime') ? ' has-error' : '' }}">
            <label for="delivery_date" class="col-md-4 control-label">◎お届け希望日　　　</label>
            <input id="delivery_date" type="date" class="form-control" name="delivery_date" value={{old('delivery_date')}}>
            @if ($errors->has('delivery_date'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('delivery_date') }}</strong>
                </span>
            @elseif($errors->has('wk_delivery_datetime'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('wk_delivery_datetime') }}</strong>
                </span>
            @endif
        </div>
        </div>

        <div class="form-group{{ $errors->has('delivery_time') ? ' has-error' : '' }}">
        <div class="form-group{{ $errors->has('wk_delivery_datetime') ? ' has-error' : '' }}">
            <label for="delivery_time" class="col-md-4 control-label">◎お届け希望時間帯　</label>
            <select id="delivery_time" class="form-control" name="delivery_time">
                <option value='' @if(old('delivery_time')=='') selected  @endif>選択してください</option>
                <option value='0:00〜2:00' @if(old('delivery_time')=='0:00〜2:00') selected  @endif>0:00〜2:00</option>
                <option value='2:00〜4:00' @if(old('delivery_time')=='2:00〜4:00') selected  @endif>2:00〜4:00</option>
                <option value='4:00〜6:00' @if(old('delivery_time')=='4:00〜6:00') selected  @endif>4:00〜6:00</option>
                <option value='6:00〜8:00' @if(old('delivery_time')=='6:00〜8:00') selected  @endif>6:00〜8:00</option>
                <option value='8:00〜10:00' @if(old('delivery_time')=='8:00〜10:00') selected  @endif>8:00〜10:00</option>
                <option value='10:00〜12:00' @if(old('delivery_time')=='10:00〜12:00') selected  @endif>10:00〜12:00</option>
                <option value='12:00〜14:00' @if(old('delivery_time')=='12:00〜14:00') selected  @endif>12:00〜14:00</option>
                <option value='14:00〜16:00' @if(old('delivery_time')=='14:00〜16:00') selected  @endif>14:00〜16:00</option>
                <option value='16:00〜18:00' @if(old('delivery_time')=='16:00〜18:00') selected  @endif>16:00〜18:00</option>
                <option value='18:00〜20:00' @if(old('delivery_time')=='18:00〜20:00') selected  @endif>18:00〜20:00</option>
                <option value='20:00〜22:00' @if(old('delivery_time')=='20:00〜22:00') selected  @endif>20:00〜22:00</option>
                <option value='22:00〜24:00' @if(old('delivery_time')=='22:00〜24:00') selected  @endif>22:00〜24:00</option>
            </select>
            @if ($errors->has('delivery_time'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('delivery_time') }}</strong>
                </span>
            @elseif($errors->has('wk_delivery_datetime'))
                <span class="c-help-block">
                    <strong>{{ $errors->first('wk_delivery_datetime') }}</strong>
                </span>
            @endif
        </div>
        </div>

        <div class="form-group">
            <button type="submit" class="c-button-type1-4 u-mt-50">
                支払い方法指定へ
            </button>
        </div>
    </form>
    <div class="col-md-6">
        <button class="c-button-type1-1 u-mt-50" type="button" onclick=history.back()>戻る</button>
    </div>
</div>
@endsection
