<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Multi Auth Guard') }}</title>

    <!-- Styles -->
    <!--link href="/css/app.css" rel="stylesheet"-->
    <!--link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"-->
    <link href="/css/member.css" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!-- Java Script -->
    <script type="application/javascript" src="/js/address_get.js"></script>

</head>
<body>
    <div class="l-member-header">
        @if (Auth::guest())
            <a class="l-member-header__title u-margin--r-50" href="{{ url('/') }}">{{ config('app.name', 'Laravel Multi Auth Guard') }}</a>  <!-- .envにあるAPP_NAMEを取得 -->
            <form method="GET" action="{{ url('/keyword') }}">
                <input class="l-member-header--search-box" placeholder="商品をキーワードで検索" type="text" class="form-control" name="product_search_keyword" value={{old('product_search_keyword')}}>
                <button type="submit" class="c-button--type2-4">検索</button>
            </form>
            <a class="l-member-header__line u-margin--r-50" href="{{ url('/member/login') }}">ログイン</a>
            <a class="l-member-header__line u-margin--r-50" href="{{ url('/member/register/in') }}">新規会員登録</a>
        @else
            <a class="l-member-header__title u-margin--r-50" href="{{ url('/member/home') }}">{{ config('app.name', 'Laravel Multi Auth Guard') }}</a>  <!-- .envにあるAPP_NAMEを取得 -->
            <form method="GET" action="{{ url('/member/keyword') }}">
                <input class="l-member-header--search-box" placeholder="商品をキーワードで検索" type="text" class="form-control" name="product_search_keyword" value={{old('product_search_keyword')}}>
                <button type="submit" class="c-button--type2-4">検索</button>
            </form>
            <a class="l-member-header__line u-margin--r-50">{{ "こんばんは、".Auth::user()->last_name." ".Auth::user()->first_name."さん" }}</a>
            <a class="l-member-header__line u-margin--r-50" href="{{ url('/member/cart_index') }}"　onclick="event.preventDefault(); document.getElementById('member-cart-form').submit();">
                カート一覧へ
            </a>
            <a class="l-member-header__line u-margin--r-50" href="{{ url('/member/menu') }}"　onclick="event.preventDefault(); document.getElementById('member-menu-form').submit();">
                会員メニュー
            </a>
            <form id="member-menu-form" action="{{ url('/member/menu') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <a class="l-member-header__line u-margin--r-50" href="{{ url('/member/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        ログアウト
            </a>
            <form id="logout-form" action="{{ url('/member/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endif
    </div>

    @yield('content')

    <div class="l-member-footer">
    </div>
    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
