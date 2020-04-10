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
            <div class="header-box">
                @if (Auth::guest())
                    <a class="header-line title-font" href="{{ url('/') }}">{{ config('app.name', 'Laravel Multi Auth Guard') }}</a>  <!-- .envにあるAPP_NAMEを取得 -->
                @else
                    <a class="header-line title-font" href="{{ url('/member/home') }}">{{ config('app.name', 'Laravel Multi Auth Guard') }}</a>  <!-- .envにあるAPP_NAMEを取得 -->
                @endif
                <ul class="header-line">
                    <li class="header-line">
                        @if (Auth::guest())
                            <form class="header-line" method="GET" action="{{ url('/keyword') }}">
                                <input placeholder="商品をキーワードで検索" id="product_search_keyword" type="text" class="form-control" name="product_search_keyword" value={{old('product_search_keyword')}}>
                                <input type="submit" name="search" value="検索">
                            </form>
                        @else
                            <form class="header-line" method="GET" action="{{ url('/member/keyword') }}">
                                <input placeholder="商品をキーワードで検索" id="product_search_keyword" type="text" class="form-control" name="product_search_keyword" value={{old('product_search_keyword')}}>
                                <input type="submit" name="search" value="検索">
                            </form>
                        @endif
                    </li>
                    @if (Auth::guest())
                        <li class="header-line">
                            <a href="{{ url('/member/login') }}">ログイン</a>
                        </li>
                        <li class="header-line">
                            <a href="{{ url('/member/registerin') }}">新規会員登録</a>
                        </li>
                    @else
                        <li class="header-line">
                            {{ "こんにちは、".Auth::user()->last_name." ".Auth::user()->first_name."さん" }}
                        </li>
                        <li class="header-line">
                            <a href="{{ url('/member/cart_index') }}"　onclick="event.preventDefault(); document.getElementById('member-cart-form').submit();">
                                カート一覧へ
                            </a>
                            <!--<form id="member-cart-form" action="{{ url('/member/cart_index') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>-->
                        </li>
                        <li class="header-line">
                            <a href="{{ url('/member/menu') }}"　onclick="event.preventDefault(); document.getElementById('member-menu-form').submit();">
                                会員メニュー
                            </a>
                            <form id="member-menu-form" action="{{ url('/member/menu') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        <li class="header-line">
                            <a href="{{ url('/member/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        ログアウト
                            </a>

                            <form id="logout-form" action="{{ url('/member/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            </div>

    @yield('content')

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
