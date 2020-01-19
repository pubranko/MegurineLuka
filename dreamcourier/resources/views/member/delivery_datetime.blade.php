@extends('member.layout.auth')

@section('content')
@include('member.subviews.menu_bar')

<div class="mbr-message-box">
    商品の配達時間帯を指定してください。
</div>

    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_datetime') }}">
        {{ csrf_field() }}

            <div class="form-group">
                <button type="submit" class="delivery-button">
                    支払い方法選択へ
                </button>
            </div>
    </form>
    <div class="col-md-6">
        <button class="delivery-button" type="button" onclick=history.back()>戻る</button>
    </div>

@endsection
