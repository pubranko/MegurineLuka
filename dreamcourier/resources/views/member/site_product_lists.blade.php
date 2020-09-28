@extends('member.layout.auth')

@section('content')

<div class="l-body-flex">
    @include('member.subviews.sidebar')

    <div class="l-main u-mt-300 u-ml-1000">
        @foreach($wk_lists as $list)
            <p class="c-category-heading u-mtb-50">{{$list["introduction_tag"]}}</p>
            <ul class="p-product-lists u-m-0">
                @foreach ($list["wk_products"] as $wk_product)
                    @if (Auth::guest())
                        <a class="u-m-0" href="{{ url('/show?id='.$wk_product["id"]) }}">
                    @else
                        <a class="u-m-0" href="{{ url('/member/show?id='.$wk_product["id"]) }}">
                    @endif
                    <li class="p-product-lists__line u-mr-30">
                        <div><img class="c-img_product_thumbnail" src="{{url($wk_product["wk_product_thumbnail"])}}"></div>
                        <p class="p-product-lists__text u-mtb-10">{{$wk_product["product_code"]}}</p>
                        <p class="p-product-lists__text u-mtb-10">{{$wk_product["product_name"]}}</p>
                        <p class="p-product-lists__text u-mtb-10">{{$wk_product["product_price"]}} 円</p>
                        <p class="p-product-lists__text u-mtb-10">{{$wk_product["wk_product_stock_quantity_status"]}}</p>
                    </li>
                    </a>
                @endforeach
            </ul>
        @endforeach
        @empty($list["links"]) @else {{ $list["links"] }} @endif
    </div>
</div>


<!--div>
フォント確認用
<p class="u-test1">大見出し</p>
<p class="u-test2">中見出し</p>
<p class="u-test3">小見出し</p>
<p >通常</p>
</div-->


@endsection
