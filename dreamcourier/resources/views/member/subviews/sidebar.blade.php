<div class="l-member-sidebar u-margin--t-250">
    <ul class="l-member-sidebar__lists">
        <li class="l-member-sidebar__heading">＜カテゴリー＞</li>
        @if (Auth::guest())
            <li class="l-member-sidebar__line"><a class="l-member-sidebar__text" href="/">HOME</a></li>
        @else
            <li class="l-member-sidebar__line"><a class="l-member-sidebar__text" href="/member/home">HOME</a></li>
        @endif
        @foreach($wk_side_bar_lists as $list)
            @if (Auth::guest())
                <li class="l-member-sidebar__line"><a class="l-member-sidebar__text" href="/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @else
                <li class="l-member-sidebar__line"><a class="l-member-sidebar__text" href="/member/keyword?product_search_tag={{$list}}">{{$list}}</a></li>
            @endif
        @endforeach
    </ul>
</div>
