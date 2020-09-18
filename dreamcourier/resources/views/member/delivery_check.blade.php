@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal">
    <div class="c-message-type1">
        下記の内容でお間違えがないでしょうか？<br/>
        ご確認の上、「上記の内容で購入」ボタンを押してください。
        @foreach($errors->all() as $error)
            <div>
                <a>※エラー発生：</a><a>{{$error}}</a><br/>
            </div>
        @endforeach
    </div>
    <p>◎商品情報</p>
    <div class="delivery_check">
        <div class="l-cart-lists">
            <img class="c-img_product_thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}">
            <div class="l-product-details">
                <div class="l-cart-lists__product-name">
                    <a>【商品名】{{$wk_product['product_name']}}</a>
                </div>
                <div class="l-cart-lists__product-code">
                    <a>【商品コード】{{$wk_product['product_code']}}</a>
                </div>
                <div class="l-cart-lists__product-price">
                    <a>販売価格：{{$wk_product['product_price']}} 円</a>
                </div>
                <div class="l-cart-lists__product-stock_quantity_status">
                    <a>販売状況：{{$wk_product['wk_product_stock_quantity_status']}}</a>
                </div>
            </div>
        </div>
    </div>
    <p>◎配達先情報</p>
    <div class="delivery_check">
        <table class="c-table-type1">
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">受取人氏名</th>
                <td class="c-table-type1__cell">{{$wk_delivery_destination['receiver_name']}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">郵便番号</th>
                <td class="c-table-type1__cell">{{$wk_delivery_destination['postal_code1']}}-{{$wk_delivery_destination['postal_code2']}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">住所　</th>
                <td class="c-table-type1__cell">
                    {{$wk_delivery_destination['address1']}}
                    {{$wk_delivery_destination['address2']}}
                    {{$wk_delivery_destination['address3']}}
                    {{$wk_delivery_destination['address4']}}<a>　</a>
                    {{$wk_delivery_destination['address5']}}
                    {{$wk_delivery_destination['address6']}}
                </td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">連絡先電話番号</th>
                <td class="c-table-type1__cell">{{$wk_delivery_destination['phone_number1']}} - {{$wk_delivery_destination['phone_number2']}} - {{$wk_delivery_destination['phone_number3']}}</td>
            </tr>
        </table>
    </div>

    <p>◎配達日時</p>
    <div class="delivery_check">
        <table class="c-table-type1">
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">配達希望日</th>
                <td class="c-table-type1__cell">{{$wk_datetime['delivery_date_edit']}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">配達希望時間帯</th>
                <td class="c-table-type1__cell">{{$wk_datetime['delivery_time']}}</td>
            </tr>
        </table>
    </div>

    <p>◎支払い方法</p>
    <div class="delivery_check">
        <table class="c-table-type1">
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">クレジットカード番号</th>
                <td class="c-table-type1__cell">{{$wk_credit_card['card_number']}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">有効期限（月/年）</th>
                <td class="c-table-type1__cell">{{$wk_credit_card['card_month']}}/{{$wk_credit_card['card_year']}}</td>
            </tr>
            <tr class="c-table-type1__cell">
                <th class="c-table-type1__cell">名義人氏名</th>
                <td class="c-table-type1__cell">{{$wk_credit_card['card_name']}}</td>
            </tr>
        </table>
    </div>

    <form id="member-address-form" method="POST" action="{{ url('/member/delivery_register') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <button type="submit" class="c-button-type1-4">
                上記の内容で購入
            </button>
        </div>
    </form>
    <div class="col-md-6">
        <button class="c-button-type1-4" type="button" onclick=history.back()>戻る</button>
    </div>
</div>
@endsection
