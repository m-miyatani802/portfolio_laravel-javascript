@extends('layouts.app')

@section('title')
    type
@endsection

@section('script')
    <script>
        let Q = @JSON($items);
        let Q_No = 0; //問題をランダムで出題する.
        let Q_No_max = Q.length;

        let Q_NoAother = Math.floor(Math.random() * Q.length);

        let Q_i = 0; //回答初期値・現在単語どこまで当たっているか判断している文字番号
        let Q_l = Q[Q_No]['typing'].length; //計算用の文字の長さ

        window.addEventListener("keydown", push_keydown);

        function push_keydown(event) {
            let keyCode = event.key;
            if (Q_l == Q_l - Q_i) {

                //問題を書き出す

                document.getElementById("tagu").innerHTML = Q[Q_No].reading;
                document.getElementById("word").innerHTML = Q[Q_No].phrases;
                document.getElementById("start").innerHTML = Q[Q_No].typing.substring(Q_i, Q_l);
                document.getElementById("meaning").innerHTML = Q[Q_No].meaning;
            }

            // 押したキーが合っていたら
            if (Q[Q_No]['typing'].charAt(Q_i) == keyCode) {

                // 判定する文章に1足す
                Q_i++;

                // 問題を書き出す
                // document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
                document.getElementById("start").innerHTML = Q[Q_No]['typing'].substring(Q_i, Q_l);

                // 全部正解したら
                if (Q_l - Q_i === 0) {

                    // 問題をランダムで出題する
                    // Q_No = Math.floor(Math.random() * Q.length);
                    Q_No++;

                    // 回答初期値・現在どこまであっているか判定している文字番号
                    Q_i = 0;
                    // 計算用の文字の長さ
                    Q_l = Q[Q_No]['typing'].length;

                    // 新たな問題を書きだす
                    document.getElementById("tagu").innerHTML = Q[Q_No].reading;
                    document.getElementById("word").innerHTML = Q[Q_No].phrases;
                    document.getElementById("start").innerHTML = Q[Q_No].typing.substring(Q_i, Q_l);
                    document.getElementById("meaning").innerHTML = Q[Q_No].meaning;
                }
            }
        }
    </script>
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">タイピング</div>
                <div class="card-body">
                    <h2 id="tagu" class="text"></h2>
                    <h1 id="word"></h1>
                    <h1 id="start" class="text">何かキーを押してください</h1>
                    <h5 id="meaning"></h5>
                </div>
            </div>
            <form action="#">
                <button class="btn btn-outline-primary" onClick="history.back(); return false;">戻る</button>
            </form>
        </div>
    </div>
@endsection
