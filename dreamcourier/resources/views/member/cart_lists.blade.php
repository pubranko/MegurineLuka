@extends('member.layout.auth')

@section('content')

<div class="l-body-nomal u-margin--t-350 u-margin--l-100">
    @foreach($wk_products as $wk_product)
        <div class="p-cart-box u-margin--t-70">
            <img class="p-product-details__thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}">
            <div class="p-cart-box__layout u-margin--l-70">
                <div class="p-cart-box__product-name u-margin--t-30">
                    <a>【商品名】{{$wk_product['product_name']}}</a>
                </div>
                <div class="p-cart-box__product-code u-margin--t-60">
                    <a>【商品コード】{{$wk_product['product_code']}}</a>
                </div>
                <div class="p-cart-box__product-price u-margin--t-60">
                    <a>【販売価格】{{$wk_product['product_price']}} 円</a>
                </div>
                <div class="p-cart-box__product-stock_quantity_status u-margin--t-60">
                    <a>【販売状況】{{$wk_product['wk_product_stock_quantity_status']}}</a>
                </div>
            </div>
            <div class="p-cart-box__layout u-margin--l-70">
                <a class="c-button--type2-1 u-margin--t-100" href="/member/delivery_address?cartlist_id={{$wk_product['cartlist_id']}}">購入手続きへ</a>
                <a class="c-button--type2-2 u-margin--t-130" href="/member/cart_delete?cartlist_id={{$wk_product['cartlist_id']}}">キャンセル</a>
            </div>
        </div>
    @endforeach

    <div>
        @empty($cart_lists) @else {{ $cart_lists->links() }} @endif
    </div>
</div>

@endsection
