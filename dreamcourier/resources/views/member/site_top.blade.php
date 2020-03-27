@extends('member.layout.auth')

@section('content')

<div class=side-bar-fixed>
    <div class=side-bar-box>
        <ul>
            <li><b>＜カテゴリー＞</b></li>
            <li><a href="/">HOME</a></li>
            <li><a href="/keyword?product_search_tag=ギャンブル">ギャンブル</a></li>
            <li><a href="/keyword?product_search_tag=異世界転生">異世界転生</a></li>
        </ul>
    </div>

    <div>
        @foreach($wk_lists as $list)
            <h1 class="category-message">{{$list["introduction_tag"]}}</h1>
            <ul class="product-contents">
                @foreach ($list["wk_products"] as $wk_product)
                    @if (Auth::guest())
                        <a href="{{ url('/show?id='.$wk_product["id"]) }}">
                    @else
                        <a href="{{ url('/member/show?id='.$wk_product["id"]) }}">
                    @endif
                    <li>
                        <div><img class="img_product_thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}"></div>
                        <p>{{$wk_product["product_code"]}}</p>
                        <p>{{$wk_product["product_name"]}}</p>
                        <p>{{$wk_product["product_price"]}} 円</p>
                        <p>{{$wk_product["wk_product_stock_quantity_status"]}}</p>
                    </li>
                    </a>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>

@endsection
