@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　オペレーターメニュー</div>

                <div class="panel-body">
                    <div class="menu_box">
                        <h1 class="menu_name">会員管理</h1>
                        <!--form role="form" method="GET" action="{{ url('/operator/member') }}"-->
                            <input type="submit" name="search" value="検索">
                            <input type="submit" name="register_in" value="登録">
                        <!--/form-->
                    </div>
                    <div class="menu_box">
                        <h1 class="menu_name">取引管理</h1>
                        <!--form role="form" method="GET" action="{{ url('/operator/transaction') }}"-->
                            <input type="submit" name="search" value="検索">
                            <input type="submit" name="register_in" value="登録">
                        <!--/form-->
                    </div>
                </div>
                <div class="panel-body">
                    <div class="menu_box">
                        <h1 class="menu_name">商品管理</h1>
                        <form role="form" method="GET" action="{{ url('/operator/product/menu') }}">
                            <input type="submit" name="product_menu" value="検索">
                            <input type="submit" name="product_menu" value="登録">
                        </form>
                    </div>
                    <div class="menu_box">
                        <h1 class="menu_name">お知らせ管理</h1>
                        <!--form role="form" method="GET" action="{{ url('/operator/notice') }}"-->
                            <input type="submit" name="search" value="検索">
                            <input type="submit" name="register" value="登録">
                        <!--/form-->
                    </div>
                </div>
                <div class="panel-body">
                    <div class="menu_box">
                        <h1 class="menu_name">入出金管理</h1>
                        <!--form role="form" method="GET" action="{{ url('/operator/deposits_withdrawals') }}"-->
                            <input type="submit" name="search" value="検索">
                            <input type="submit" name="deposit_register_in" value="入金登録">
                            <input type="submit" name="withdraw_register_in" value="出勤登録">
                        <!--/form-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
