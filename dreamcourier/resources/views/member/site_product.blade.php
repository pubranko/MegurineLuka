@extends('member.layout.auth')

@section('content')
<div class="l-body-flex"W>
    @include('member.subviews.sidebar')

    <div class="l-main u-margin--t-300 u-margin--l-1000">
        <div class="c-product-tag u-margin--tb-50">
            <a>【商品タグ】</a>
            @foreach($wk_product["wk_product_tag_lists"] as $wk_product_tag)
                @if (Auth::guest())
                    <a class="c-product-tag__text u-margin--r-30" href="/keyword?product_search_tag={{$wk_product_tag}}" class="">{{$wk_product_tag}}</a>
                @else
                    <a class="c-product-tag__text u-margin--r-30" href="/member/keyword?product_search_tag={{$wk_product_tag}}" class="">{{$wk_product_tag}}</a>
                @endif
            @endforeach
        </div>

        <img class="c-product-details__image" src="{{url($wk_product["wk_product_image"])}}">

        <div class="c-product-details u-margin--l-70">
            <div class="c-product-details__name u-margin--t-20">
                <a>【商品名】{{$wk_product["product_name"]}}</a>
            </div>
            <div class="c-product-details__description u-margin--t-50">
                <a>【商品説明】{{$wk_product["product_description"]}}</a>
            </div>
            <div class="c-product-details__code u-margin--t-50">
                <a>【商品コード】{{$wk_product["product_code"]}}</a>
            </div>
            <div class="c-product-details__price u-margin--r-110 u-margin--t-50">
                <a>【販売価格】{{$wk_product["product_price"]}} 円</a>
            </div>
            <div class="c-product-details__stock_quantity_status ">
                <a>【販売状況】{{$wk_product["wk_product_stock_quantity_status"]}}</a>
            </div>
        </div>

        <div>
            @if (Auth::guest())
            @else
                <a class="c-button--type2-1 u-margin--t-50" 
                    @if($wk_product["wk_product_stock_quantity_status"] == "販売中止") 
                    @elseif($wk_product["wk_product_stock_quantity_status"] == "在庫なし") 
                    @else
                        href="/member/cart_add?id={{$wk_product["id"]}}" 
                    @endif
                    >カートへ追加
                </a>
                @isset ($wk_product["cart_add_flg"])
                    <a class="c-button--type2-1" href="/member/cart_index">購入手続きへ</a>
                @endisset
            @endif
            <div>
                <button  class="c-button--type2-3 u-margin--t-50" type="button" onclick=history.back()>戻る</button>
            </div>
        </div>
    </div>
</div>
@endsection
