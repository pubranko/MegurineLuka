@extends('member.layout.auth')

@section('content')
@include('member.subviews.menu_bar')

<div class="show-product-tag">
    <a>【商品タグ】</a>
    <ul>
        @foreach($wk_product_tag_lists as $wk_product_tag)
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

<img class="show-product-image" src="{{url($wk_product_image)}}">

<div class="product-info">
    <div class="show-product-name">
        <a>【商品名】{{$product_name}}</a>
    </div>
    <div class="show-product-description">
        <a>【商品説明】{{$product_description}}</a>
    </div>
    <div class="show-product-code">
        <a>【商品コード】{{$product_code}}</a>
    </div>
    <div class="show-product-price">
        <a>販売価格：{{$product_price}} 円</a>
    </div>
    <div class="show-product-stock_quantity_status">
        <a>販売状況：{{$wk_product_stock_quantity_status}}</a>
    </div>
</div>

<div>
@if (Auth::guest())
@else
    <div class="show-product-botton show-product-botton-add" >
        <a  @if($wk_product_stock_quantity_status == "販売中止") 
            @elseif($wk_product_stock_quantity_status == "在庫なし") 
            @else
                href="/member/cart_add?id={{$id}}" 
            @endif
            >カートへ追加
        </a>
    </div>
    @isset ($cart_add_flg)
        <div class="show-product-botton show-product-botton-add" >
            <a  href="/member/cart_index">購入手続きへ</a>
        </div>
    @endisset
@endif
<div>
    <button  class="show-product-botton" type="button" onclick=history.back()>戻る</button>
</div>
</div>
@endsection
