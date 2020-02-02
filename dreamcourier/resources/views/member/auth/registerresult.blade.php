@extends('member.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">夢の宅配便　会員新規登録（結果）</div>
                <div class="panel-body">
                    <div class="mbr-message-box">
                        <a>夢の宅配便への入会ありがとうございます。<br/>></a>
                        <a>入会の手続きは終わりましたが、お買い物をしていただくには支払方法の登録が必要です。<br/>></a>
                        <a>よろしければ、支払方法のご登録を下記のボタンより行ってください。</a>
                    </div>
                    <form class="form-horizontal" role="form" method="GET" action="{{ url('あとで決める') }}">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    支払い方法登録へ(※フェーズ２以降で実装予定)
                                </button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/member/home') }}">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    あとで登録する
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
