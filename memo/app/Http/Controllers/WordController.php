<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Word;
use App\Http\Requests\InquiryRequest;
use App\Models\FavoritesUser;
use App\Libs\Common;
use App\Libs\Search;
use Illuminate\Support\Facades\Mail;
use App\Mail\OpinionMail;

class WordController extends Controller
{
    /**
     * すべての単語を取得し、トップページへ遷移.
     * 
     * @return \Illuminate\Http\Response
     */
    public function topPage(Request $request)
    {
        $login_session = Common::loginSession($request);
        $items = [
            'user_id' => $login_session['user_id'],
            'mylists' => $login_session['mylist'],
            'words' => Word::sortable()->orderBy('created_at', 'desc')->simplePaginate(10),
        ];
        $sort = null;
        return view('top', compact('items', 'sort'));
    }

    /**
     * トップページのソート反映用
     */
    public function sortTopPage(Request $request)
    {
        $login_session = Common::loginSession($request);
        $sort = $request->sort;
        $items = [
            'user_id' => $login_session['user_id'],
            'mylists' => $login_session['mylist'],
            'words' => Word::sortable()->orderBy($sort, 'desc')->simplePaginate(10),
        ];
        return view('top', compact('items', 'sort'));
    }

    /**
     * ログイン後itemsを送りマイページへ遷移
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $login_session = Common::loginSession($request);
        $sort = null;
        $items = [
            'user_id' => $login_session['user_id'],
            'mylists' =>  $login_session['mylist'],
            'words' =>  Word::where('user_id', '=', $login_session['user_id'])->orderBy('created_at', 'desc')->simplePaginate(10),
        ];
        return view('index', compact('items', 'sort'));
    }

    /**
     * マイページのソート機能用
     */
    public function sortIndex(Request $request)
    {
        $login_session = Common::loginSession($request);
        $sort = $request->sort;

        $items = [
            'user_id' => $login_session['user_id'],
            'mylists' => $login_session['mylist'],
            'words' => Word::where('user_id', '=', $login_session['user_id'])->sortable()->orderBy($sort, 'desc')->simplePaginate(10),
        ];
        return view('index', compact('items', 'sort'));
    }

    /**
     * 単語登録画面へ遷移.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * requestされた情報をもとに、単語を登録し、マイページへ遷移.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_session = Common::loginSession($request);
        $array = Common::isWordsNull($request);

        try {
            DB::beginTransaction();

            $word = new Word();
            $word->create([
                'user_id' => $login_session['user_id'],
                'reading' => $array['reading'],
                'phrases' => $array['phrases'],
                'meaning' => $array['meaning'],
                'typing' => $array['typing'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\WordController@index');
    }

    /**
     * word_idからwordモデルを使い詳細画面へ遷移.
     *
     * @param  Word $word
     * @return \Illuminate\Http\Response
     */
    public function show(Word $word, Request $request)
    {
        $login_session = Common::loginSession($request);
        $item = [
            'user_id' => $login_session['user_id'],
            'word' => $word,
            'mylists' => $login_session['mylist'],
        ];
        return view('show', compact('item'));
    }

    /**
     * トップページ用の詳細画面へ遷移.
     */
    public function topShow(Request $request)
    {
        $login_session = Common::loginSession($request);
        // $user_id = Auth::id();
        $item = [
            'user_id' => $login_session['user_id'],
            'word' => Word::where('id', $request->id)->first(),
            'mylists' => $login_session['mylist'],
        ];
        return view('show', compact('item'));
    }

    /**
     * word_idをもとにwordモデルを使い単語編集画面へ遷移.
     *
     * @param  Word $word --- word_id
     * @return \Illuminate\Http\Response
     */
    public function edit(Word $word, Request $request)
    {
        $item['user_id'] = $request->session()->get('login.user_id');
        return view('edit', compact('word', 'item'));
    }

    /**
     * requestをもとに変更点だけをupdateし、マイページにredirectする.
     *
     * @param  \Illuminate\Http\Request  $request --reading, phrases, meaning.
     * @param  int  $id ---word_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $array = Common::isWordsNull($request);

        try {
            DB::beginTransaction();

            Word::where('id', $id)->update([
                'reading' => $array['reading'],
                'phrases' => $array['phrases'],
                'meaning' => $array['meaning'],
                'typing' => $array['typing'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\WordController@index');
    }

    /**
     * 単語削除の確認画面へ遷移する
     * 
     * @param  \Illuminate\Http\Request --- word_id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = [
            'id' => $request->id,
            'page' => 'word',
        ];
        return view('yes_or_no', compact('items'));
    }

    /**
     * 単語削除確認後、単語を削除しマイページにredirectする
     *
     * @param Word $word -- word_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Word $word)
    {
        try {
            Word::where('id', $word->id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->action('App\Http\Controllers\WordController@index');
    }


    /**
     * requestされたパラメータを使い検索結果を送り、遷移する
     * 
     * @param  \Illuminate\Http\Request  $request --- search_word, word_search, user_search, reading, phrases, meaning.
     * @return \Illuminate\Http\Response
     */
    public function searchResults(Request $request)
    {
        $search_word = $request->search_word;
        $words_search = $request->words_search;
        $users_search = $request->users_search;
        $reading = $request->reading;
        $phrases = $request->phrases;
        $meaning = $request->meaning;
        $login_session = $request->session()->get('login');
        $items = Search::searchVariable($search_word, $users_search, $words_search, $reading, $phrases, $meaning, $login_session);

        return view('search.search_result', compact('items', 'words_search', 'reading', 'phrases', 'meaning', 'search_word'));
    }

    /**
     * ユーザーidを使い全単語を取得し、そのユーザーページに遷移
     * 
     * @param  \Illuminate\Http\Request  $request --- user_id
     * @return \Illuminate\Http\Response
     */
    public function userPage(Request $request)
    {
        $items = [
            'words' => Word::where('user_id', $request->user_id)->get(),
            'mylists' => DB::table('mylists')->where('user_id', Auth::id())->get(),
            'user_id' => Auth::id(),
            'other_user_id' => $request->user_id,
            'other_user_name' => $request->user_name,
        ];
        return view('other_user_page', compact('items'));
    }

    public function inquiry()
    {
        return view('inquiry.index');
    }
    public function inquirySend(InquiryRequest $request)
    {
        // dd($request);
        $name = $request->name;
        $email = $request->email;
        $inquiry = $request->inquiry;

        Mail::to($email)->send(new OpinionMail($name, $email, $inquiry));
        return view('inquiry.complete');
    }
}
