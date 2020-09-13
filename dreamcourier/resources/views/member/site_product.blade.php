@extends('member.layout.auth')

@section('content')
<div class=l-contents-flex>
    @include('member.subviews.side_bar')

    <div class=l-center-box>
        <div class="show-product-tag">
            <a>【商品タグ】</a>
            <ul>
                @foreach($wk_product["wk_product_tag_lists"] as $wk_product_tag)
                    <li>
                        @if (Auth::guest())
                            <a href="/keyword?product_search_tag={{$wk_product_tag}}" class="">{{$wk_product_tag}}</a>
                        @else
                            <a href="/member/keyword?product_search_tag={{$wk_product_tag}}" class="">{{$wk_product_tag}}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <img class="c-img_product_image" src="{{url($wk_product["wk_product_image"])}}">

        <div class="product-info">
            <div class="show-product-name">
                <a>【商品名】{{$wk_product["product_name"]}}</a>
            </div>
            <div class="show-product-description">
                <a>【商品説明】{{$wk_product["product_description"]}}</a>
            </div>
            <div class="show-product-code">
                <a>【商品コード】{{$wk_product["product_code"]}}</a>
            </div>
            <div class="show-product-price">
                <a>販売価格：{{$wk_product["product_price"]}} 円</a>
            </div>
            <div class="show-product-stock_quantity_status">
                <a>販売状況：{{$wk_product["wk_product_stock_quantity_status"]}}</a>
            </div>
        </div>

        <div>
            @if (Auth::guest())
            @else
                <div class="c-button-type1-2" >
                    <a  @if($wk_product["wk_product_stock_quantity_status"] == "販売中止") 
                        @elseif($wk_product["wk_product_stock_quantity_status"] == "在庫なし") 
                        @else
                            href="/member/cart_add?id={{$wk_product["id"]}}" 
                        @endif
                        >カートへ追加
                    </a>
                </div>
                @isset ($wk_product["cart_add_flg"])
                    <div class="c-button-type1-2" >
                        <a  href="/member/cart_index">購入手続きへ</a>
                    </div>
                @endisset
            @endif
            <div>
                <button  class="c-button-type1-1" type="button" onclick=history.back()>戻る</button>
            </div>
        </div>
    </div>
</div>
@endsection
