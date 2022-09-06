<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Word;
use App\Models\User;
use App\Models\Typing;
use App\Http\Requests\registerRequest;

class WordController extends Controller
{
    /**
     * すべての単語を取得し、ページへ遷移.
     * 
     * @return \Illuminate\Http\Response
     */
    public function topPage()
    {
        $user_id = Auth::id();
        $items = [
            'user_id' => Auth::id(),
            'mylists' => DB::table('mylists')->where('user_id', Auth::id())->get(),
            'words' => Word::orderBy('created_at', 'desc')->simplePaginate(10),
        ];
        return view('top', compact('items'));
    }

    /**
     * ログイン後itemsを送りマイページへ遷移
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();

        $items = [
            'user_id' => $user_id,
            'mylists' =>  DB::table('mylists')->where('user_id', $user_id)->get(),
            'words' =>  Word::where('user_id', '=', $user_id)->orderBy('created_at', 'desc')->simplePaginate(10),
        ];

        return view('index', compact('items'));
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
    public function store(registerRequest $request)
    {
        if ($request->reading == null)
            $request->reading = '';
        if ($request->phrases == null)
            $request->phrases = '';
        if ($request->meaning == null)
            $request->meaning = '';

        $word = new Word();
        $word->create([
            'user_id' => Auth::id(),
            'reading' => $request->reading,
            'phrases' => $request->phrases,
            'meaning' => $request->meaning,
        ]);


        // dd($request->typing);
        if (!$request->typing === '') {
            $word1 = Word::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first('id');

            $typing = new Typing();
            $typing->create([
                'word_id' => $word1->id,
                'typing_character' => $request->typing,
            ]);
        } elseif ($request->typing === '') {
            $word1 = Word::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first('id');
            $$typing->create([
                'word_id' => $word1->id,
                'typing_character' => '',
            ]);
        }

        return redirect()->action('App\Http\Controllers\WordController@index');
    }

    /**
     * word_idからwordモデルを使い詳細画面へ遷移.
     *
     * @param  Word $word
     * @return \Illuminate\Http\Response
     */
    public function show(Word $word)
    {
        $user_id = Auth::id();
        $item = [
            'user_id' => Auth::id(),
            'word' => $word,
            'mylists' => DB::table('mylists')->where('user_id', $user_id)->get(),
            'typ' => Typing::where('word_id', $word->id)->first(),

        ];
        return view('show', compact('item'));
    }

    /**
     * トップページ用の詳細画面へ遷移.
     */
    public function topShow(Request $request)
    {
        $user_id = Auth::id();
        $item = [
            'user_id' => Auth::id(),
            'word' => Word::where('id', $request->id)->first(),
            'mylists' => DB::table('mylists')->where('user_id', $user_id)->get(),
            'typ' => Typing::where('word_id', $request->id)->first(),
        ];
        return view('show', compact('item'));
    }

    /**
     * word_idをもとにwordモデルを使い単語編集画面へ遷移.
     *
     * @param  Word $word --- word_id
     * @return \Illuminate\Http\Response
     */
    public function edit(Word $word)
    {
        $typing = Typing::where('word_id', $word->id)->first();
        // dd($typing);
        return view('edit', compact('word', 'typing'));
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

        if ($request->reading == null)
            $request->reading = '';
        if ($request->phrases == null)
            $request->phrases = '';
        if ($request->meaning == null)
            $request->meaning = '';

        Word::where('id', $id)->update([
            'reading' => $request->reading,
            'phrases' => $request->phrases,
            'meaning' => $request->meaning,
        ]);

        $typ = Typing::where('word_id', $id)->get();
        // dd($typ);

        if (count($typ) == 0 && !$request->typing == null) {
            $typing = new Typing();
            $typing->create([
                'word_id' => $id,
                'typing_character' => $request->typing,
            ]);
        } elseif (!count($typ) == 0 && !$request->typing == null) {
            Typing::where('word_id', $id)->update([
                'word_id' => $id,
                'typing_character' => $request->typing,
            ]);
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
        Word::where('id', $word->id)->delete();
        typing::where('word_id', $word->id)->delete();
        return redirect()->action('App\Http\Controllers\WordController@index');
    }


    /**
     * requestされたパラメータを使い検索結果を送り、遷移する
     * 
     *
     * 
     * @param  \Illuminate\Http\Request  $request --- search_word, word_search, user_search, reading, phrases, meaning.
     * @return \Illuminate\Http\Response
     */
    public function searchResults(Request $request)
    {
        $search_word = $request->search_word;
        $word = Word::query();
        if ($request->users_search == "1" && $request->words_search == "1") {
            if ($request->reading == '1') {
                $word = $word->orWhere('reading', 'like', '%' . $search_word . '%');
            }
            if ($request->phrases == "1") {
                $word = $word->orWhere('phrases', 'like', '%' . $search_word . '%');
            }
            if ($request->meaning == "1") {
                $word = $word->orWhere('meaning', 'like', '%' . $search_word . '%');
            }


            $rec = $word->simplePaginate(10);
            $words_search = $request->words_search;
            $reading = $request->reading;
            $phrase = $request->phrases;
            $meaning = $request->meaning;

            $items = [
                'users' => User::where('name', 'like', '%' . $search_word . '%')->simplePaginate(5),
                'words' => $rec,
                'mylists' => DB::table('mylists')->where('user_id', Auth::id())->get(),
                'user_id' => AUth::id(),
            ];
            return view('search.search_result', compact('items', 'words_search', 'reading', 'phrase', 'meaning', 'search_word'));
        } elseif ($request->users_search == "1") {
            $items = [
                'users' => User::where('name', 'like', '%' . $search_word . '%')->simplePaginate(10),
                'mylists' => DB::table('mylists')->where('user_id', Auth::id())->get(),
                'user_id' => AUth::id(),
            ];
            return view('search.search_result', compact('items'));
        } elseif ($request->words_search == "1") {

            if ($request->reading == '1') {
                $word = $word->orWhere('reading', 'like', '%' . $search_word . '%');
            }
            if ($request->phrases == "1") {
                $word = $word->orWhere('phrases', 'like', '%' . $search_word . '%');
            }
            if ($request->meaning == "1") {
                $word = $word->orWhere('meaning', 'like', '%' . $search_word . '%');
            }

            $rec = $word->simplePaginate(10);
            $words_search = $request->words_search;
            $reading = $request->reading;
            $phrase = $request->phrases;
            $meaning = $request->meaning;

            $items = [
                'words' => $rec,
                'mylists' => DB::table('mylists')->where('user_id', Auth::id())->get(),
                'user_id' => AUth::id(),
            ];
            return view('search.search_result', compact('items', 'words_search', 'reading', 'phrase', 'meaning', 'search_word'));
        } else {
            $items = [
                'error' => 'ユーザー名ボタンか単語ボタンを押してください。'
            ];
            return view('search.search_result', compact('items'));
        }
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
            'other_user_id' => Word::where('user_id', $request->id)->first(),
            'other_user_name' => $request->user_name,
        ];
        return view('other_user_page', compact('items'));
    }
}
