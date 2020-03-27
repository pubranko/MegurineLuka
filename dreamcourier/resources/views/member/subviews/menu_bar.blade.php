@if (Auth::guest())
    <a href="/" class="btn-gradient-3d">HOME</a>
    <a href="/keyword?product_search_tag=ギャンブル" class="btn-gradient-3d">ギャンブル</a>
    <a href="/keyword?product_search_tag=異世界転生" class="btn-gradient-3d">異世界転生</a>
    <a href="/keyword" class="btn-gradient-3d">その他</a>
@else
    <a href="/member/home" class="btn-gradient-3d">HOME</a>
    <a href="/member/keyword?product_search_tag=ギャンブル" class="btn-gradient-3d">ギャンブル</a>
    <a href="/member/keyword?product_search_tag=異世界転生" class="btn-gradient-3d">異世界転生</a>
    <a href="/member/keyword" class="btn-gradient-3d">その他</a>
@endif
