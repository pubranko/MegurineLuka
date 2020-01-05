@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_仮登録（結果）</div>

                <div class="panel-body">

                    <a>商品情報の<strong><u>仮登録</u></strong>が終わりました。</a>
                    <a>上席者の方へ<strong><u>仮登録の承認</u></strong>を依頼してください。</a>

                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/product/register/in') }}">
                            <input type="submit" class="form-control" value="引き続き商品情報の仮登録を行う">
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/home') }}">
                            <input type="submit" class="form-control" value="TOPページへ戻る">
                        </form>
                    </div>
                    <div class="col-md-6">
                        <!-- 参照は後日実装 -->
                        <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/home') }}">
                            <input type="submit" class="form-control" name="product_register_reference" value="登録した商品情報を参照する">（後日実装予定）
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
