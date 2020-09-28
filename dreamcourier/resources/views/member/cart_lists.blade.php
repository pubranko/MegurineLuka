@extends('member.layout.auth')

@section('content')

<div class="l-body-nomal u-mt-350 u-ml-100">
    @foreach($wk_products as $wk_product)
        <div class="p-cart-box u-mt-70">
            <img class="c-img_product_thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}">
            <div class="p-cart-box__layout u-ml-70">
                <div class="p-cart-box__product-name u-mb-40">
                    <a>【商品名】{{$wk_product['product_name']}}</a>
                </div>
                <div class="p-cart-box__product-code u-mb-40">
                    <a>【商品コード】{{$wk_product['product_code']}}</a>
                </div>
                <div class="p-cart-box__product-price u-mb-40">
                    <a>【販売価格】{{$wk_product['product_price']}} 円</a>
                </div>
                <div class="p-cart-box__product-stock_quantity_status">
                    <a>【販売状況】{{$wk_product['wk_product_stock_quantity_status']}}</a>
                </div>
            </div>
            <div class="p-cart-box__layout u-ml-70">
                <div class="c-button-type1-2 u-mt-50" >
                    <a  href="/member/delivery_address?cartlist_id={{$wk_product['cartlist_id']}}">配送手続きへ</a>
                </div>
                <div class="c-button-type1-3 u-mt-50" >
                    <a  href="/member/cart_delete?cartlist_id={{$wk_product['cartlist_id']}}">キャンセル</a>
                </div>
            </div>
        </div>
    @endforeach

    <div>
        @empty($cart_lists) @else {{ $cart_lists->links() }} @endif
    </div>
</div>

@endsection
