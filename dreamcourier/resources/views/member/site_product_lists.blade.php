@extends('member.layout.auth')

@section('content')

<div class=contents-flex>
    <div class=side-bar-box>
        <ul>
            <li><b>＜カテゴリー＞</b></li>
            @if (Auth::guest())
                <li><a href="/">HOME</a></li>
                @foreach($wk_side_bar_lists as $list)
                    <li><a href="/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
                @endforeach
            @else
                <li><a href="/member/home">HOME</a></li>
                @foreach($wk_side_bar_lists as $list)
                    <li><a href="/member/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
                @endforeach
            @endif
        </ul>
    </div>

    <div class=center-box>
        @foreach($wk_lists as $list)
            <p class="category-message">{{$list["introduction_tag"]}}</p>
            <ul class="product-contents">
                @foreach ($list["wk_products"] as $wk_product)
                    @if (Auth::guest())
                        <a href="{{ url('/show?id='.$wk_product["id"]) }}">
                    @else
                        <a href="{{ url('/member/show?id='.$wk_product["id"]) }}">
                    @endif
                    <li>
                        <div><img class="img_product_thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}"></div>
                        <p class="product-contents-text">{{$wk_product["product_code"]}}</p>
                        <p class="product-contents-text">{{$wk_product["product_name"]}}</p>
                        <p class="product-contents-text">{{$wk_product["product_price"]}} 円</p>
                        <p class="product-contents-text">{{$wk_product["wk_product_stock_quantity_status"]}}</p>
                    </li>
                    </a>
                @endforeach
            </ul>
        @endforeach
        @empty($list["links"]) @else {{ $list["links"] }} @endif
    </div>
</div>

@endsection
