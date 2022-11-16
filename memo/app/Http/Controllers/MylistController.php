<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  App\Libs\Common;
use Carbon\Carbon;

class MylistController extends Controller
{
    /**
     * 自身のuser_idを使いマイリストを取得し遷移.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = null;
        $mylists = DB::table('mylists')->join('users', 'mylists.user_id', '=', 'users.id')
            ->select('mylists.*', 'mylists.name as mylist_name', 'mylists.id as mylist_id', 'users.id as user_id', 'users.name')
            ->where('mylists.user_id', $request->session()->get('login.user_id'))
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10);
        return view('mylist.listIndex', compact('mylists', 'sort'));
    }

    /**
     * マイリストページのソート用関数
     */
    public function sortIndex(Request $request)
    {
        $sort = $request->sort;
        $mylists = DB::table('mylists')->join('users', 'mylists.user_id', '=', 'users.id')
            ->select('mylists.*', 'mylists.name as mylist_name', 'mylists.id as mylist_id', 'users.id as user_id', 'users.name')
            ->where('mylists.user_id', $request->session()->get('loign.user_id'))
            ->orderBy($sort, 'desc')
            ->simplePaginate(10);
        return view('mylist.listIndex', compact('mylists', 'sort'));
    }

    /**
     * mylist_idを使いマイリスト内の単語を取得し遷移.
     * @param  \Illuminate\Http\Request  $request --- mylist_id
     * @return \Illuminate\Http\Response
     */
    public function wordIndex(Request $request, $mylist_id)
    {
        $login_session = Common::loginSession($request);
        $items = [
            'words' => DB::table('mylists_words')->join('mylists', 'mylists_words.mylist_id', '=', 'mylists.id')
                ->join('words', 'mylists_words.word_id', 'words.id')
                ->join('users', 'words.user_id', '=', 'users.id')
                ->select('mylists_words.*', 'mylists_words.created_at', 'mylists.name as mylist_name', 'users.id as user_id', 'users.name as user_name', 'words.user_id as words_user_id', 'words.id', 'words.reading', 'words.phrases', 'words.meaning')
                ->where('mylist_id', $mylist_id)
                ->orderBy('mylists_words.created_at', 'desc')
                ->simplePaginate(10),
            'mylists' => $login_session['mylist'],
            'user_id' => $login_session['user_id'],
        ];
        $mylist_name = DB::table('mylists')->where('id', '=', $mylist_id)->first();

        return view('mylist.wordListIndex', compact('items', 'mylist_id', 'mylist_name'));
    }

    public function wordIndexSort(Request $request)
    {
        $login_session = Common::loginSession($request);
        // if ($request->sort === 'created_at') {
        //     $sort = 'mylists_words.$request->sort';
        // } else {
        //     $sort = 'words.$request->sort';
        // }
        $sort = Common::mylistsWordsSort($request);
        // dd($sort);
        $items = [
            'words' => DB::table('mylists_words')->join('mylists', 'mylists_words.mylist_id', '=', 'mylists.id')
                ->join('words', 'mylists_words.word_id', 'words.id')
                ->join('users', 'words.user_id', '=', 'users.id')
                ->select('mylists_words.*', 'mylists_words.created_at', 'mylists.name as mylist_name', 'users.id as user_id', 'users.name as user_name', 'words.user_id as words_user_id', 'words.id', 'words.reading', 'words.phrases', 'words.meaning')
                ->where('mylist_id', $request->id)
                ->orderBy($sort['0'].=$sort['1'], 'desc')
                ->simplePaginate(10),
            'mylists' => $login_session['mylist'],
            'user_id' => $login_session['user_id'],
        ];

        $mylist_name = DB::table('mylists')->where('id', '=', $request->id)->first();
        $mylist_id = $request->id;
        // dd($items['words']);
        return view('mylist.wordListIndex', compact('items', 'mylist_id', 'mylist_name', 'sort'));
    }

    /**
     * mylist_idとword_idを使いマイリストに単語を登録しマイリスト内の単語一覧画面へ遷移.
     * @param  \Illuminate\Http\Request  $request --- word_id, mylist_id
     */
    public function wordRegister(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('mylists_words')->insert([
                'word_id' => $request->word_id,
                'mylist_id' => $request->mylist_id,
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->action('App\Http\Controllers\MylistController@index');
    }

    /**
     * マイリスト作成画面へ遷移.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mylist.mylist_create');
    }

    /**
     * requestをもとにマイリストを登録し、マイページへredirectする.
     * @param  \Illuminate\Http\Request  $request --- mylist_name
     */
    public function store(request $request)
    {
        $login_session = Common::loginSession($request);
        try {
            DB::beginTransaction();
            DB::table('mylists')->insert([
                'name' => $request->mylist_name,
                'user_id' => $login_session['user_id'],
            ]);
            $mylist = DB::table('mylists')->where('user_id', $login_session['user_id'])->get();
            $request->session()->put('login.mylist', $mylist);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\MylistController@index');
    }

    /**
     * requestをもとにマイリスト情報取得し編集画面へ遷移.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nameEdit(Request $request)
    {
        $item = DB::table('mylists')->where('id', $request->id)->first();
        return view('mylist.mylist_nameEdit', compact('item'));
    }

    /**
     * requestをもとにマイリストをupdateしマイリスト一覧へredirectする
     * @param  \Illuminate\Http\Request  $request --- mylist_id, mylist_name
     */
    public function nameUpdate(Request $request)
    {
        $login_session = Common::loginSession($request);
        try {
            DB::beginTransaction();
            DB::table('mylists')->where('id', $request->mylist_id)->update([
                'name' => $request->mylist_name,
                'user_id' => $request->session()->get('login.user_id'),
            ]);
            $mylist = DB::table('mylists')->where('user_id', $login_session['user_id'])->get();
            $request->session()->put('login.mylist', $mylist);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->action('App\Http\Controllers\MylistController@index');
    }

    /**
     * マイリスト削除確認ページへ遷移.
     * @param  \Illuminate\Http\Request  $request --- mylist_id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items =  [
            'id' => $request->id,
            'page' => 'mylist',
        ];

        return view('yes_or_no', compact('items'));
    }

    /**
     * requestをもとにマイリストを削除し、削除したidを持マイリスト内の単語を削除し,マイリスト一覧へredirectする.
     * あえてAuthを使った処理
     * @param  \Illuminate\Http\Request  $request --- mylist_id
     */
    public function deleteAction(Request $request)
    {
        $login_session = Common::loginSession($request);
        try {
            DB::beginTransaction();
            DB::table('mylists')->where('id', $request->id)->where('user_id', AUth::id())->delete();
            DB::table('mylists_words')->where('mylist_id', $request->id)->delete();
            $mylist = DB::table('mylists')->where('user_id', $login_session['user_id'])->get();
            $request->session()->put('login.mylist', $mylist);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\MylistController@index');
    }

    /**
     * マイリスト内の単語を削除確認画面へ遷移する.
     * @param  \Illuminate\Http\Request  $request --- word_id, mylist_id
     * @return \Illuminate\Http\Response
     */
    public function wordDelete(Request $request)
    {
        $items = [
            'word_id' => $request->word_id,
            'mylist_id' => $request->mylist_id,
            'page' => 'mylist_word'
        ];
        return view('yes_or_no', compact('items'));
    }

    /**
     * word_idとmylist_idをもとにmylists_wordsを削除しマイリスト内単語一覧にredirectする.
     * @param  \Illuminate\Http\Request  $request --- word_id, mylist_id
     */
    public function wordDeleteAction(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('mylists_words')->where('word_id', $request->word_id)
                ->where('mylist_id', $request->mylist_id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\MylistController@wordIndex', $request->mylist_id);
    }
}
