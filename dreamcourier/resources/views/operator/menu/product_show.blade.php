@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_参照</div>

                <div class="panel-body">

                    <div class="form-group{{ $errors->has('product_code') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">商品コード</label>

                        <div class="col-md-6">
                            <a>　{{ $product_code }}　</a>

                            @if ($errors->has('product_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('product_code') }}</strong>
                                </span>
                            @endif
                        </div>
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
                        <a>{{$wk_product_thumbnail_original_filename}}</a>
                    </div>
                    <div class="col-md-6">
                        <img class="img_product_thumbnail" src="{{$wk_product_thumbnail_pathname_client}}">
                    </div>

                    <label class="col-md-4 control-label">商品画像</label>
                    <div class="col-md-6">
                        <a>{{$wk_product_image_original_filename}}</a>
                    </div>
                    <div class="col-md-6">
                        <img class="img_product_image" src="{{$wk_product_image_pathname_client}}">
                    </div>

                    <label class="col-md-4 control-label">商品検索キーワード</label>
                    <div class="col-md-6">
                        <a>　{{$product_search_keyword}}　</a>
                    </div>

                    <label class="col-md-4 control-label">商品タグ</label>
                    <div class="col-md-6">
                        <a>　{{$product_tag}}　</a>
                    </div>

                    <div class="form-group{{ $errors->has('sales_period_date_from') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('sales_period_time_from') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('wk_sales_period_from') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">販売期間（ＦＲＯＭ）</label>
                        <div class="col-md-6">
                            <a>　{{$wk_sales_period_from}}　</a>

                            @if ($errors->has('sales_period_date_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sales_period_date_from') }}</strong>
                                </span>
                            @elseif ($errors->has('sales_period_time_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sales_period_time_from') }}</strong>
                                </span>
                            @elseif ($errors->has('wk_sales_period_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('wk_sales_period_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    </div>
                    </div>

                    <div class="form-group{{ $errors->has('sales_period_date_to') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('sales_period_time_to') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('wk_sales_period_to') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">販売期間（ＴＯ）</label>

                        <div class="col-md-6">
                            <a>　{{$wk_sales_period_to}}　</a>

                            @if ($errors->has('sales_period_date_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sales_period_date_to') }}</strong>
                                </span>
                            @elseif ($errors->has('sales_period_time_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sales_period_time_to') }}</strong>
                                </span>
                            @elseif ($errors->has('wk_sales_period_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('wk_sales_period_to') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    </div>
                    </div>

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/operator/product/register') }}">
                        {{ csrf_field() }}

                        <!-- ボタン -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    上記の内容で登録する
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-6">
                        <button type="button" onclick=history.back()>戻る</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
