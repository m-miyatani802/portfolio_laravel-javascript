<?php

namespace App\Libs;

class Common
{
    // セッションにある情報を取得
    static public function loginSession($request)
    {
        if ($request->session()->get('login') === null) {
            $login_session = [
                'user_id' => '',
                'mylist' => '',
                'favorites_user' => '',
            ];
        } else {
            $login_session = $request->session()->get('login');
        }
        return $login_session;
    }

    // requestで送られて来ない箇所に''を代入
    static public function isWordsNull($request)
    {
        if ($request->reading == null)
            $request->reading = '';
        if ($request->phrases == null)
            $request->phrases = '';
        if ($request->meaning == null)
            $request->meaning = '';
        if ($request->typing == null)
            $request->typing = '';

        $array = [
            'reading' => $request->reading,
            'phrases' => $request->phrases,
            'meaning' => $request->meaning,
            'typing' => $request->typing,
        ];

        return $array;
    }

    // mylists_wordsテーブルのorderBy()で検索する為に変換する.
    static public function mylistsWordsSort($request)
    {
        if ($request->sort === 'created_at') {
            $sort = [
                '0' => 'mylists_words.',
                '1' => $request->sort,
            ];
        } else {
            $sort = [
                '0' => 'words.',
                '1' => $request->sort,
            ];
        }
        return $sort;
    }
}
