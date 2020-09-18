<div class=l-member-sidebar>
    <ul class=l-member-sidebar__lists>
        <li class=l-member-sidebar__line><b>＜カテゴリー＞</b></li>
        @if (Auth::guest())
            <li class=l-member-sidebar__line><a href="/">HOME</a></li>
        @else
            <li class=l-member-sidebar__line><a href="/member/home">HOME</a></li>
        @endif
        @foreach($wk_side_bar_lists as $list)
            @if (Auth::guest())
                <li class=l-member-sidebar__line><a href="/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @else
                <li class=l-member-sidebar__line><a href="/member/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @endif
        @endforeach
    </ul>
</div>
