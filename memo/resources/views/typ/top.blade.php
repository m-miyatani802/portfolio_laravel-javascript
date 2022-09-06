@extends('layouts.new')

@section('title')
    type
@endsection

@section('script')
    <script>
        let Q = @JSON($items);
        let Q_No = Math.floor(Math.random() * Q.length); //問題をランダムで出題する.

        let Q_i = 0; //回答初期値・現在単語どこまで当たっているか判断している文字番号
        let Q_l = Q[Q_No].typing_character.length; //計算用の文字の長さ


        window.addEventListener("keydown", push_keydown);

        // document.write(Q);

        function push_keydown(event) {
            let keyCode = event.key;
            if (Q_l == Q_l - Q_i) {

                //問題を書き出す

                document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
                document.getElementById("word").innerHTML = Q[Q_No].phrases.substring(Q_i, Q_l);
                document.getElementById("start").innerHTML = Q[Q_No].typing_character.substring(Q_i, Q_l);
                document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);

            }

            // 押したキーが合っていたら
            if (Q[Q_No]['typing_character'].charAt(Q_i) == keyCode) {

                // 判定する文章に1足す
                Q_i++;

                // 問題を書き出す
                // document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
                document.getElementById("start").innerHTML = Q[Q_No].typing_character.substring(Q_i, Q_l);

                // 全部正解したら
                if (Q_l - Q_i === 0) {

                    // 問題をランダムで出題する
                    Q_No = Math.floor(Math.random() * Q.length);
                    // 回答初期値・現在どこまであっているか判定している文字番号
                    Q_i = 0;
                    // 計算用の文字の長さ
                    Q_l = Q[Q_No].typing_character.length;

                    // 新たな問題を書きだす
                    document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
                    document.getElementById("word").innerHTML = Q[Q_No].phrases.substring(Q_i, Q_l);
                    document.getElementById("start").innerHTML = Q[Q_No].typing_character.substring(Q_i, Q_l);
                    document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);

                }
            }
            // document.getElementById("push").innerHTML = keyCode + "を押しました."
            // document.write(Q);
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
                    <h5 id="tagu" class="text"></h5>
                    <h1 id="word"></h1>
                    <h1 id="start" class="text">何かキーを押してください</h1>
                    <h5 id="meaning" class="text"></h5>
                </div>
            </div>
            <form action="#">
                <button class="btn btn-outline-primary" onClick="history.back(); return false;">戻る</button>
            </form>

        </div>
    </div>
@endsection
