@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_仮登録（入力）</div>

                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/operator/product/check') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('product_code') ? ' has-error' : '' }}">
                            <label for="product_code" class="col-md-4 control-label">商品コード</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}">

                                @if ($errors->has('product_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                            <label for="product_name" class="col-md-4 control-label">商品名</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_name" type="text" class="form-control" name="product_name" value={{old('product_name')}}>

                                @if ($errors->has('product_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_description') ? ' has-error' : '' }}">
                            <label for="product_description" class="col-md-4 control-label">商品説明</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_description" type="text" class="form-control" name="product_description" value={{old('product_description')}}>

                                @if ($errors->has('product_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_price') ? ' has-error' : '' }}">
                            <label for="product_price" class="col-md-4 control-label">商品価格</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_price" type="text" class="form-control" name="product_price" value={{old('product_price')}}>

                                @if ($errors->has('product_price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_stock_quantity') ? ' has-error' : '' }}">
                            <label for="product_stock_quantity" class="col-md-4 control-label">商品在庫数</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="aaproduct_stock_quantitya" type="text" class="form-control" name="product_stock_quantity" value={{old('product_stock_quantity')}}>

                                @if ($errors->has('product_stock_quantity'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_stock_quantity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_thumbnail') ? ' has-error' : '' }}">
                            <label for="product_thumbnail" class="col-md-4 control-label">商品サムネイル</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_thumbnail" type="file" class="form-control" name="product_thumbnail">

                                @if ($errors->has('product_thumbnail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_thumbnail') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_image') ? ' has-error' : '' }}">
                            <label for="product_image" class="col-md-4 control-label">商品画像</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_image" type="file" class="form-control" name="product_image">

                                @if ($errors->has('product_image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_search_keyword') ? ' has-error' : '' }}">
                            <label for="product_search_keyword" class="col-md-4 control-label">商品検索キーワード</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_search_keyword" type="text" class="form-control" name="product_search_keyword" value={{old('product_search_keyword')}}>

                                @if ($errors->has('product_search_keyword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_search_keyword') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('product_tag') ? ' has-error' : '' }}">
                            <label for="product_tag" class="col-md-4 control-label">商品タグ</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="product_tag" type="text" class="form-control" name="product_tag" value={{old('product_tag')}}>

                                @if ($errors->has('product_tag'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('product_tag') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('sales_period_date_from') ? ' has-error' : '' }}">
                        <div class="form-group{{ $errors->has('sales_period_time_from') ? ' has-error' : '' }}">
                        <div class="form-group{{ $errors->has('wk_sales_period_from') ? ' has-error' : '' }}">
                            <label for="sales_period_from" class="col-md-4 control-label">販売期間（ＦＲＯＭ）</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="sales_period_from" type="date" class="form-control" name="sales_period_date_from" value={{old('sales_period_date_from')}}>
                                <input id="sales_period_from" type="time" class="form-control" name="sales_period_time_from" value={{old('sales_period_time_from')}}>

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
                            <label for="sales_period_to" class="col-md-4 control-label">販売期間（ＴＯ）</label>

                            <div class="col-md-6">
                                <a>　必須　</a>
                                <input id="sales_period_to" type="date" class="form-control" name="sales_period_date_to" value={{old('sales_period_date_to')}}>
                                <input id="sales_period_to" type="time" class="form-control" name="sales_period_time_to" value={{old('sales_period_time_to')}}>

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


                        <!-- ボタン -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    次へ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
