<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\User;
use App\Models\Typing;
use App\Models\FavoritesWord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class TypingController extends Controller
{
    /**
     * トップページから全ユーザーが登録した単語を出力
     */
    public function top()
    {
        $items = Word::select('reading', 'phrases', 'meaning', 'id', 'typing')
            ->whereNotNull('typing')
            ->inRandomOrder()
            ->get();

        // dd($items);
        return view('typ.index', compact('items'));
    }

    /**
     * マイページから自分が登録した単語を出力
     */
    public function typing()
    {
        $items = Word::where('user_id', Auth::id())
            // ->where('typing', '!=', '')
            ->whereNotNull('typing')
            ->select('reading', 'phrases', 'meaning', 'id', 'typing')
            ->inRandomOrder()
            ->get();

        // dd($items);
        return view('typ.index', compact('items'));
    }

    /**
     * お気に入りに登録した単語を出力
     */
    public function favorite()
    {
        // $items = DB::table('favorites_words')->where('favorites_words.user_id', Auth::id())
        //     ->select('words.reading', 'words.phrases', 'words.meaning', 'typings.typing_character')
        //     ->get();

        $items = Word::leftJoin('favorites_words', 'words.id', '=', 'favorites_words.word_id')
            ->where('favorites_words.user_id', '=', Auth::id())
            ->whereNotNull('words.typing')
            ->inRandomOrder()
            ->get();


        // dd($items);
        return view('typ.index', compact('items'));
    }

    /**
     * 各マイリストごとに登録した単語を出力
     */
    public function mylist(Request $request)
    {
        $items = DB::table('mylists_words')->where('mylist_id', $request->mylist_id)
            ->join('words', 'mylists_words.word_id', '=', 'words.id')
            // ->join('typings', 'words.id', '=', 'typings.word_id')
            ->select('mylists_words.*', 'words.reading', 'words.phrases', 'words.meaning', 'words.typing')
            ->whereNotNull('words.typing')
            ->inRandomOrder()
            ->get();
        return view('typ.index', compact('items'));
    }

    /**
     * 保守用の登録ページに移動
     */
    public function typingStringRegister(Request $request)
    {
        $id = $request->id;
        return view('typ.string_register', compact('id'));
    }

    /**
     * 保守用関数の処理
     */
    public function typingStringRegisterAction(Request $request)
    {
        $sample = Typing::where('word_id', $request->word_id)->first();

        if ($sample == null) {
            $typingString = new Typing();
            $typingString->create([
                'word_id' => $request->word_id,
                'typing_character' => $request->typingCharacter,
            ]);
        }
        return redirect()->action('App\Http\Controllers\WordController@index');
    }
}
