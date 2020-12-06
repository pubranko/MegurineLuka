@extends('member.layout.auth')

@section('content')
<div class="l-body-nomal u-margin--t-350 u-margin--l-100">
    <div class="c-operation-message u-margin--tb-70">
        <a>夢の宅配便への入会ありがとうございます。<br/>></a>
        <a>入会の手続きは終わりましたが、お買い物をしていただくには支払方法の登録が必要です。<br/>></a>
        <a>よろしければ、支払方法のご登録を下記のボタンより行ってください。</a>
    </div>
    <form class="" role="form" method="GET" action="{{ url('あとで決める') }}">
        <button type="submit" class="c-button--type2-1 u-margin--tb-100">
            支払い方法登録へ(※後日実装予定の機能)
        </button>
    </form>
    <form class="" role="form" method="GET" action="{{ url('/member/home') }}">
        <button type="submit" class="c-button--type2-3">
            あとで登録する
        </button>
    </form>
</div>
@endsection
