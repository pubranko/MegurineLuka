@extends('member.layout.auth')

@section('content')

<div class=l-body-flex>
    @include('member.subviews.side_bar')

    <div class=l-main>
        @foreach($wk_lists as $list)
            <p class="c-category-message">{{$list["introduction_tag"]}}</p>
            <ul class="l-product-lists">
                @foreach ($list["wk_products"] as $wk_product)
                    @if (Auth::guest())
                        <a href="{{ url('/show?id='.$wk_product["id"]) }}">
                    @else
                        <a href="{{ url('/member/show?id='.$wk_product["id"]) }}">
                    @endif
                    <li class=l-product-lists__line>
                        <div><img class="c-img_product_thumbnail l-product-lists__img" src="{{url($wk_product["wk_product_thumbnail"])}}"></div>
                        <p class="l-product-lists__text">{{$wk_product["product_code"]}}</p>
                        <p class="l-product-lists__text">{{$wk_product["product_name"]}}</p>
                        <p class="l-product-lists__text">{{$wk_product["product_price"]}} 円</p>
                        <p class="l-product-lists__text">{{$wk_product["wk_product_stock_quantity_status"]}}</p>
                    </li>
                    </a>
                @endforeach
            </ul>
        @endforeach
        @empty($list["links"]) @else {{ $list["links"] }} @endif
    </div>
</div>

@endsection
