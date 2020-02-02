@if (Auth::guest())
    <a href="/" class="btn-gradient-3d">HOME</a>
    <a href="/tag?tag=ギャンブル" class="btn-gradient-3d">ギャンブル</a>
    <a href="/tag?tag=異世界転生" class="btn-gradient-3d">異世界転生</a>
    <a href="/tag" class="btn-gradient-3d">その他</a>
@else
    <a href="/member/home" class="btn-gradient-3d">HOME</a>
    <a href="/member/tag?tag=ギャンブル" class="btn-gradient-3d">ギャンブル</a>
    <a href="/member/tag?tag=異世界転生" class="btn-gradient-3d">異世界転生</a>
    <a href="/member/tag" class="btn-gradient-3d">その他</a>
@endif
