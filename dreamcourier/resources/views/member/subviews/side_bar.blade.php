<div class=l-side-bar-box>
    <ul>
        <li><b>＜カテゴリー＞</b></li>
        @if (Auth::guest())
            <li><a href="/">HOME</a></li>
        @else
            <li><a href="/member/home">HOME</a></li>
        @endif
        @foreach($wk_side_bar_lists as $list)
            @if (Auth::guest())
                <li><a href="/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @else
                <li><a href="/member/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @endif
        @endforeach
    </ul>
</div>
