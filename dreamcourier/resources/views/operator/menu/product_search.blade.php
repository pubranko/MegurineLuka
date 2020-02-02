@extends('operator.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　商品情報_検索</div>

                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/product/search') }}">
                        {{ csrf_field() }}

                        <table class="search_conditions">
                            <caption>○　検索条件入力</caption>
                            <thead>
                                <tr>
                                    <th>商品コード</th>
                                    <th>キーワード検索</th>
                                    <th>商品タグ</th>
                                    <th>商品在庫数（以上）</th>
                                    <th>商品在庫数（以下）</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="product_code" value=@if(count($errors)>0) "{{old('product_code')}}" @else @isset($product_code) "{{$product_code}}" @endisset @endif></td>
                                    <td><input type="text" name="product_search_keyword" value=@if(count($errors)>0) "{{old('product_search_keyword')}}" @else @isset($product_search_keyword) "{{$product_search_keyword}}" @endisset @endif></td>
                                    <td><input type="text" name="product_tag" value=@if(count($errors)>0)"{{old('product_tag')}}" @else @isset($product_tag) "{{$product_tag}}" @endisset @endif></td>
                                    <td><input type="text" name="product_stock_quantity_from" value=@if(count($errors)>0){{old('product_stock_quantity_from')}}@else @isset($product_stock_quantity_from){{$product_stock_quantity_from}}@endisset @endif></td>
                                    <td><input type="text" name="product_stock_quantity_to" value=@if(count($errors)>0){{old('product_stock_quantity_to')}}@else @isset($product_stock_quantity_to){{$product_stock_quantity_to}}@endisset @endif></td>
                                </tr>
                            </tbody>
                            <thead>
                                    <tr>
                                        <th>販売期間FROM</th>
                                        <th>販売期間TO</th>
                                        <th>販売状況</th>
                                        <th>ステータス</th>
                                        <th></th>
                                    </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="date" name="sales_period_date_from" value=@if(count($errors)>0) {{ old('sales_period_date_from') }} @else @isset($sales_period_date_from) {{$sales_period_date_from}} @endisset  @endif>
                                        <input type="time" name="sales_period_time_from" value=@if(count($errors)>0) {{ old('sales_period_time_from') }} @else @isset($sales_period_time_from) {{$sales_period_time_from}} @endisset  @endif>
                                    </td>
                                    <td>
                                        <input type="date" name="sales_period_date_to" value=@if(count($errors)>0) {{ old('sales_period_date_to') }} @else @isset($sales_period_date_to) {{$sales_period_date_to}} @endisset  @endif>
                                        <input type="time" name="sales_period_time_to" value=@if(count($errors)>0) {{ old('sales_period_time_to') }} @else @isset($sales_period_time_to) {{$sales_period_time_to}} @endisset  @endif>
                                    </td>
                                    <td>
                                        <!--販売状況のチェックボックス：'販売可','仮販売中止','販売中止','仮販売再開'の４種を用意する。-->
                                        <!--入力値時のチェックを維持する。またエラー時はチェック状態を復元する。-->
                                        @foreach(['販売可','仮販売中止','販売中止','仮販売再開'] as $pattern)
                                            <input type="checkbox" name="selling_discontinued_classification[]" value="{{$pattern}}"
                                            @isset($selling_discontinued_classification)
                                                @foreach($selling_discontinued_classification as $st)
                                                    @if($pattern == $st) checked="" @endif
                                                @endforeach
                                            @endisset
                                            @if (is_array(old("selling_discontinued_classification")) && in_array("$pattern", old('selling_discontinued_classification'), true)) checked @endif
                                            >{{$pattern}}
                                            @if($pattern == "仮販売中止")
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <!--ステータスのチェックボックス：正式、仮登録、仮変更の３種を用意する。-->
                                        <!--入力値時のチェックを維持する。またエラー時はチェック状態を復元する。-->
                                        @foreach(['正式','仮登録','仮変更'] as $pattern)
                                            <input type="checkbox" name="status[]" value="{{$pattern}}"
                                            @isset($status)
                                                @foreach($status as $st)
                                                    @if($pattern == $st) checked="" @endif
                                                @endforeach
                                            @endisset
                                            @if (is_array(old("status")) && in_array("$pattern", old('status'), true)) checked @endif
                                            >{{$pattern}}
                                        @endforeach
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="search_conditions">
                            <th>表示明細数</th>
                            <td><input type="text" name="product_list_details" value=@if(count($errors)>0){{old('product_list_details')}}@else @isset($product_list_details){{$product_list_details}}@else "20" @endisset @endif></td>
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
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach

                    <hr class="separator_line">

                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/product/approval') }}">
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
                                    <th>変更</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($search_queries as $search_query)
                                    <tr>
                                        <td><input type="checkbox" name="select_id[]" value="{{$search_query->id}}" @if (is_array(old("select_id")) && in_array("$search_query->id", old('select_id'), true)) checked @endif></td>
                                        <td>{{$search_query->product_code}}</td>
                                        <td>{{$search_query->product_name}}</td>
                                        <td>{{$search_query->product_tag}}</td>
                                        <td>{{substr($search_query->sales_period_from,0,-3)."〜".substr($search_query->sales_period_to,0,-3)}}</td>
                                        <td>{{$search_query->status}}</td>
                                        <td>{{$search_query->selling_discontinued_classification}}</td>
                                        <td>{{$search_query->product_price}}</td>
                                        <td>{{$search_query->product_stock_quantity}}</td>
                                        <td>
                                            <input type="button" value="参照" onclick="f_select_link('/operator/product/show?',{{$search_query->id}})">
                                        </td>
                                        <td>未実装</td>
                                        <td>未実装</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="submit" name="product_menu" value="承認">
                    </form>

                    @empty($search_queries) @else {{ $search_queries->links() }} @endif

                    <!-- ボタン -->
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" method="GET" action="{{ url('/operator/home') }}">
                            <input type="submit" value="TOPページへ戻る">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
