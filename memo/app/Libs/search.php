<?php

namespace App\Libs;

use App\Models\Word;
use App\Models\User;
use  App\Libs\Common;




class Search
{
    static public function searchVariable($search_word, $users_search, $words_search, $reading, $phrases, $meaning, $login_session)
    {
        if($login_session === null)
        {
            $login_session = [
                'user_id' => '',
                'mylist' => '',
                'favorites_users' => '',
            ];
        }
        $word = Word::query();
        if ($users_search == "1" && $words_search == "1") {
            if ($reading == '1') {
                $word = $word->orWhere('reading', 'like', '%' . $search_word . '%');
            }
            if ($phrases == "1") {
                $word = $word->orWhere('phrases', 'like', '%' . $search_word . '%');
            }
            if ($meaning == "1") {
                $word = $word->orWhere('meaning', 'like', '%' . $search_word . '%');
            }

            // dd($login_session);
            $rec = $word->simplePaginate(10);
            $items = [
                'users' => User::where('name', 'like', '%' . $search_word . '%')->simplePaginate(5),
                'words' => $rec,
                'mylists' => $login_session['mylist'],
                'user_id' => $login_session['user_id'],
                'favorites_users' => $login_session['favorites_users']

            ];
            return $items;
        } elseif ($users_search == "1") {
            $items = [
                'users' => User::where('name', 'like', '%' . $search_word . '%')->simplePaginate(10),
                'mylists' => $login_session['mylist'],
                'user_id' => $login_session['user_id'],
                'favorites_users' => $login_session['favorites_users']
            ];
            // dd($items);
            return $items;
        } elseif ($words_search == "1") {
            if ($reading == '1') {
                $word = $word->orWhere('reading', 'like', '%' . $search_word . '%');
            }
            if ($phrases == "1") {
                $word = $word->orWhere('phrases', 'like', '%' . $search_word . '%');
            }
            if ($meaning == "1") {
                $word = $word->orWhere('meaning', 'like', '%' . $search_word . '%');
            }

            if (empty($login_session['user_id']) === true) {
                $user_id = null;
            }

            $rec = $word->simplePaginate(10);

            $items = [
                'words' => $rec,
                'mylists' => $login_session['mylist'],
                'user_id' => $login_session['user_id'],
            ];
            return $items;
        } else {
            $items = [
                'error' => 'ユーザー名ボタンか単語ボタンを押してください。'
            ];
            return $items;
        }
    }
}
