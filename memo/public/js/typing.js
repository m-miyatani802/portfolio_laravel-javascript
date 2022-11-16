let Q = JSON.stringify($items);
let Q_No = Math.floor(Math.random() * Q.length); //問題をランダムで出題する.

let Q_NoAother = Math.floor(Math.random() * Q.length);

let Q_i = 0; //回答初期値・現在単語どこまで当たっているか判断している文字番号
let Q_l = Q[Q_No]['typing'].length; //計算用の文字の長さ


window.addEventListener("keydown", push_keydown);

// document.write(Q);

function push_keydown(event) {
    let keyCode = event.key;
    if (Q_l == Q_l - Q_i) {

        //問題を書き出す

        document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
        document.getElementById("word").innerHTML = Q[Q_No].phrases.substring(Q_i, Q_l);
        document.getElementById("start").innerHTML = Q[Q_No].typing.substring(Q_i, Q_l);
        // if (!Q[Q_No].meaning === null) {
        //  document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);
        // }
        document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);
    }

    // 押したキーが合っていたら
    if (Q[Q_No]['typing'].charAt(Q_i) == keyCode) {

        // 判定する文章に1足す
        Q_i++;

        // 問題を書き出す
        // document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
        document.getElementById("start").innerHTML = Q[Q_No].typing.substring(Q_i, Q_l);

        // 全部正解したら
        if (Q_l - Q_i === 0) {

            // 問題をランダムで出題する
            while (Q_No === Q_NoAother) {
                Q_NoAother = Math.floor(Math.random() * Q.length);
            }
            Q_No = Q_NoAother;
            // Q_No = Math.floor(Math.random() * Q.length);

            // 回答初期値・現在どこまであっているか判定している文字番号
            Q_i = 0;
            // 計算用の文字の長さ
            Q_l = Q[Q_No]['typing'].length;

            // 新たな問題を書きだす
            // if (!Q[Q_No].meaning === null) {
            document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);
            // }
            document.getElementById("tagu").innerHTML = Q[Q_No].reading.substring(Q_i, Q_l);
            document.getElementById("word").innerHTML = Q[Q_No].phrases.substring(Q_i, Q_l);
            document.getElementById("start").innerHTML = Q[Q_No].typing.substring(Q_i, Q_l);
            // document.getElementById("meaning").innerHTML = Q[Q_No].meaning.substring(Q_i, Q_l);

        }
    }
}