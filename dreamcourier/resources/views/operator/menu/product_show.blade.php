@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_参照</div>

                <div class="panel-body">

                    <label class="col-md-4 control-label">商品コード</label>
                    <div class="col-md-6">
                        <a>　{{ $product_code }}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品名</label>
                    <div class="col-md-6">
                        <a>　{{$product_name}}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品説明</label>
                    <div class="col-md-6">
                        <a>{{$product_description}}</a>
                    </div>

                    <label class="col-md-4 control-label">商品価格</label>
                    <div class="col-md-6">
                        <a>　{{$product_price}}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品在庫数</label>
                    <div class="col-md-6">
                        <a>　{{$product_stock_quantity}}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品サムネイル</label>
                    <div class="col-md-6">
                        <img class="img_product_thumbnail" src="{{$product_thumbnail}}">
                    </div>

                    <label class="col-md-4 control-label">商品画像</label>
                    <div class="col-md-6">
                        <img class="img_product_image" src="{{$product_image}}">
                    </div>

                    <label class="col-md-4 control-label">商品検索キーワード</label>
                    <div class="col-md-6">
                        <a>　{{$product_search_keyword}}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品タグ</label>
                    <div class="col-md-6">
                        <a>　{{$product_tag}}　</a>
                    </div>

                    <label class="col-md-4 control-label">販売期間（ＦＲＯＭ）</label>
                    <div class="col-md-6">
                        <a>　{{$sales_period_from}}　</a>
                    </div>

                    <label class="col-md-4 control-label">販売期間（ＴＯ）</label>
                    <div class="col-md-6">
                        <a>　{{$sales_period_to}}　</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
