<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoritesWord;
use App\Models\FavoritesUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Word;
use App\Models\User;
use App\Libs\Common;



class FavoriteController extends Controller
{
    /**
     * 自身のuser_idを使いお気に入り登録した単語を取得し,お気に入りページへ遷移
     * 
     * @return \Illuminate\Http\Response
     */
    public function wordsIndex(Request $request)
    {
        $login_session = Common::loginSession($request);
        $sort = null;
        $items = [
            'favorites_words' => Word::leftJoin('favorites_words', 'words.id', '=', 'favorites_words.word_id')
                ->select('words.id', 'words.reading', 'words.phrases', 'words.meaning', 'words.user_id as words_user_id', 'favorites_words.user_id as favorites_words_user_id')
                ->where('favorites_words.user_id', $login_session['user_id'])
                ->orderBy('words.created_at', 'desc')->simplePaginate(10),
            'user_id' => $login_session['user_id'],
            'mylists' =>  DB::table('mylists')->where('user_id', $login_session['user_id'])->get(),
        ];
        return view('favorite.word', compact('items', 'sort'));
    }

    /**
     * お気に入りページのソート機能用
     */
    public function sortWordsIndex(Request $request)
    {
        $login_session = Common::loginSession($request);
        $sort = $request->sort;
        $items = [
            'favorites_words' => Word::leftJoin('favorites_words', 'words.id', '=', 'favorites_words.word_id')
                ->select('words.id', 'words.reading', 'words.phrases', 'words.meaning', 'words.user_id as words_user_id', 'favorites_words.user_id as favorites_words_user_id')
                ->where('favorites_words.user_id', $login_session['user_id'])
                ->orderBy('words.created_at', 'desc')->simplePaginate(10),
            'user_id' => $login_session['user_id'],
            'mylists' =>  DB::table('mylists')->where('user_id', $login_session['user_id'])->get(),
        ];
        return view('favorite.word', compact('items', 'sort'));
    }


    /**
     * word_idとuser_idを使いお気に入りに登録し、単語のお気に入り一覧ページへ遷移.
     * @param  \Illuminate\Http\Request  $request --- word_id
     * @return \Illuminate\Http\Response
     */
    public function wordRegister(Request $request)
    {
        try {
            DB::beginTransaction();
            $favorite = new FavoritesWord();
            $str = FavoritesWord::whereUser_id(Auth::id())->whereWord_id($request->word_id)->first();
            if (!empty($str)) {
                DB::rollBack();
                return redirect()->action('App\Http\Controllers\FavoriteController@wordsIndex');
            } elseif (empty($str)) {
                $favorite->create([
                    'user_id' => Auth::id(),
                    'word_id' => $request->word_id,
                ]);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\FavoriteController@wordsIndex');
    }


    /**
     * word_idとuser_idを使いお気に入りから削除し、単語のお気に入り一覧画面へ遷移.
     * @param  \Illuminate\Http\Request  $request --- user_id
     * @return \Illuminate\Http\Response
     */
    public function wordDelete(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = Auth::id();
            $favorite = new FavoritesWord();
            $favorite->where('word_id', $request->id)->where('user_id', $user_id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\FavoriteController@wordsIndex');
    }

    /**
     * 自身のuser_idを使いお気に入りしたユーザーを取得し遷移.
     * @return \Illuminate\Http\Response
     */
    public function usersIndex(Request $request)
    {
        $login_session = Common::loginSession($request);
        $items = FavoritesUser::join('users', 'favorites_users.other_user_id', '=', 'users.id')
            ->select('favorites_users.user_id as favorites_user_id', 'favorites_users.other_user_id as favorites_other_user_id', 'users.name')
            ->where('favorites_users.user_id', $login_session['user_id'])
            ->simplePaginate();
        return view('favorite.user', compact('items'));
    }


    /**
     * other_user_idとuser_idを使いお気に入りに登録し、ユーザーのお気に入り一覧ページへ遷移.
     * @param  \Illuminate\Http\Request  $request --- other_user_id
     * @return \Illuminate\Http\Response
     */
    public function usersRegister(Request $request)
    {
        $login_session = Common::loginSession($request);
        try {
            DB::beginTransaction();
            $favorite = new FavoritesUser();
            $str = FavoritesUser::whereUser_id($login_session['user_id'])->whereOther_user_id($request->other_id)->first();

            if (!empty($str) && ($login_session['user_id'] === $request->other_id)) {
                DB::rollBack();
                return back();
                // return redirect()->action('App\Http\Controllers\FavoriteController@usersIndex');
            } elseif (empty($str)) {
                $favorite->create([
                    'user_id' => Auth::id(),
                    'other_user_id' => $request->other_id,
                ]);
                $user = FavoritesUser::where('user_id', $login_session['user_id'])->get();
                $request->session()->put('favorite_users', $user);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->action('App\Http\Controllers\FavoriteController@usersIndex');
            return back();
        }
        // return redirect()->action('App\Http\Controllers\FavoriteController@usersIndex');
        return back();
    }

    /**
     * other_user_idとuser_idを使いお気に入りから削除し、単語のお気に入り一覧画面へ遷移.
     * @param  \Illuminate\Http\Request  $request --- other_user_id
     * @return \Illuminate\Http\Response
     */
    public function usersDelete(Request $request)
    {
        // dd($request->other_user);
        $login_session = Common::loginSession($request);
        try {
            DB::beginTransaction();
            FavoritesUser::where('user_id', $login_session['user_id'])->where('other_user_id', $request->other_user)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\FavoriteController@usersIndex');
    }
}
