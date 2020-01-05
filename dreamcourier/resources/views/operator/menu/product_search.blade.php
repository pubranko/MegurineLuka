@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_検索</div>

                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/product/search') }}">

                        <table>
                            <thead>
                                <tr>
                                    <th>商品コード</th>
                                    <th>キーワード検索</th>
                                    <th>商品タグ</th>
                                    <th>販売状況</th>
                                    <th>販売期間</th>
                                    <th>ステータス</th>
                                    <th>販売中止</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>


                        <!-- ボタン -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    次へ
                                </button>
                                <div class="col-md-6">
                                    <button type="button" onclick=history.back()>戻る</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
