@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_検索</div>

                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/product/search') }}">

                        <table class="search_conditions">
                            <caption>○　検索条件入力</caption>
                            <thead>
                                <tr>
                                    <th>商品コード</th>
                                    <th>キーワード検索</th>
                                    <th>商品タグ</th>
                                    <th>ステータス</th>
                                    <th>商品在庫数</th>
                                </tr>
                                <tr>
                                    <th>販売状況</th>
                                    <th>販売期間FROM</th>
                                    <th>販売期間TO</th>
                                    <th>販売中止</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="product_code" value="{{ old('product_code') }}"></td>
                                    <td><input type="text" name="product_search_keyword" value={{old('product_search_keyword')}}></td>
                                    <td><input type="text" name="product_tag" value={{old('product_tag')}}></td>
                                    <td>
                                        <select name="status" >
                                            <option value="" @if(old('status')=='') selected  @endif>選択してください</option>
                                            <option value="正式" @if(old('status')=='正式') selected  @endif>正式</option>
                                            <option value="仮登録" @if(old('status')=='仮登録') selected  @endif>仮登録</option>
                                            <option value="仮変更" @if(old('status')=='仮変更') selected  @endif>仮変更</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="product_stock_quantity" value="{{ old('product_stock_quantity') }}"></td>

                                </tr>
                                <tr>
                                    <td>
                                        <select name="seles_status" >
                                            <option value="" @if(old('seles_status')=='') selected  @endif>選択してください</option>
                                            <option value="未販売" @if(old('seles_status')=='未販売') selected  @endif>未販売</option>
                                            <option value="販売中" @if(old('seles_status')=='販売中') selected  @endif>販売中</option>
                                            <option value="販売終了" @if(old('seles_status')=='販売終了') selected  @endif>販売終了</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="sales_period_date_from" value={{old('sales_period_date_from')}}>
                                        <input type="time" name="sales_period_time_from" value={{old('sales_period_time_from')}}>
                                    </td>
                                    <td>
                                        <input type="date" name="sales_period_date_to" value={{old('sales_period_date_to')}}>
                                        <input type="time" name="sales_period_time_to" value={{old('sales_period_time_to')}}>
                                    </td>
                                    <td>
                                        <select name="selling_discontinued_classification" >
                                            <option value="" @if(old('selling_discontinued_classification')=='') selected  @endif>選択してください</option>
                                            <option value="販売可" @if(old('selling_discontinued_classification')=='販売可') selected  @endif>販売可</option>
                                            <option value="仮販売中止" @if(old('selling_discontinued_classification')=='仮販売中止') selected  @endif>仮販売中止</option>
                                            <option value="販売中止" @if(old('selling_discontinued_classification')=='販売中止') selected  @endif>販売中止</option>
                                            <option value="仮販売再開" @if(old('selling_discontinued_classification')=='仮販売再開') selected  @endif>仮販売再開</option>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="search_conditions">
                            <th>表示明細数</th>
                            <td><input type="text" name="product_list_details" 
                                value="20"></td>
                        </table>
                        <!-- ボタン -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    検索
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr class="separator_line">

                    <table class="search_list">
                        <caption>○　商品一覧</caption>
                        <thead>
                            <tr>
                                <th>選択</th>
                                <th>商品コード</th>
                                <th>商品名</th>
                                <th>商品タグ</th>
                                <th>販売期間</th>
                                <th>ステータス</th>
                                <th>販売中止</th>
                                <th>商品価格</th>
                                <th>商品在庫数</th>
                                <th>参照</th>
                                <th>参照</th>
                                <th>変更</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td><td>test</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- ボタン -->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="col-md-6">
                                <button type="button" onclick=history.back()>戻る</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
