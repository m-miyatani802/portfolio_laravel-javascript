@extends('layouts.app')

@section('title')
    search_result
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h1>検索結果</h1>
            @if (!empty($items['users']))
                @if (count($items['users']) > 0)
                    <h2>~ユーザー一覧~</h2>
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>USER名</th>
                        </tr>
                        @foreach ($items['users'] as $item)
                            <tr>
                                <td><a
                                        href="{{ Route('other_user.page') }}?user_id={{ $item->id }}&user_name={{ $item->name }}">{{ $item->name }}</a>
                                </td>
                                @if (!empty($items['favorites_users']))
                                    @if ($items['favorites_users'] === $item->id)
                                        <td>
                                            <form action="{{ route('favorite.user_delete') }}">
                                                <input type="hidden" name="other_user" value="{{ $item->id }}">
                                            </form>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endif
            @if (isset($items['words']) && count($items['words']) > 0)
                <h2>~単語一覧~</h2>
                <table class="table table-striped table-hover">
                    <tr>
                        <th>読み方</th>
                        <th>語句</th>
                        <th>意味</th>
                        <th>登録者</th>
                    </tr>
                    @foreach ($items['words'] as $item)
                        <tr>
                            <td>{{ \Illuminate\Support\Str::limit($item->reading, 30, '...') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->phrases, 30, '...') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->meaning, 45, '...') }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td class="align-middle button">
                                <span class="btn-group">
                                    <form action="{{ route('top.show') }}" method="get">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="submit" value="詳細" class="btn btn-primary mx-sm-1">
                                    </form>

                                    @if ($items['user_id'] == $item->user_id)
                                        <form action="{{ route('words.edit', $item->id) }}" method="get">
                                            @csrf
                                            <input type="submit" class="btn btn-primary mx-sm-1" value="編集">
                                        </form>
                                    @else
                                    @endif

                                    @if (!empty($items['user_id']))
                                        <form action="{{ route('favorite.word_register') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="word_id" value="{{ $item->id }}">
                                            <input type="submit" class="btn btn-primary mx-sm-1" value="お気に入り">
                                        </form>
                                    @endif
                                    @if (!empty($items['user_id']) && count($items['mylists']) === 0)
                                        <input type="submit" class="btn btn-primary mx-sm-1" value="マイリスト登録">
                                    @elseif(!empty($items['user_id']) && count($items['mylists']) > 0)
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                mylists
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @if (empty($items['mylists']))
                                                    @foreach ($items['mylists'] as $mylist)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ Route('mylist.register') }}?word_id={{ $item->id }}&mylist_id={{ $mylist->id }}">
                                                                無し
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @elseif(!empty($items['mylists']))
                                                    @foreach ($items['mylists'] as $mylist)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ Route('mylist.register') }}?word_id={{ $item->id }}&mylist_id={{ $mylist->id }}">
                                                                {{ $mylist->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                    @if ($items['user_id'] == $item->user_id)
                                        <form action="{{ route('words.destroy', $item->id) }}" method="post">
                                            @csrf
                                            <input type="submit" class="btn btn-primary mx-sm-1" value="削除">
                                        </form>
                                    @else
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $items['words']->appends(['words_search' => $words_search, 'reading' => $reading, 'phrases' => $phrases, 'meaning' => $meaning, 'search_word' => $search_word])->links() }}
            @elseif(isset($items['error']))
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <p>{{ $items['error'] }}</p>
                    </div>
                </div>
            @else
                <p>検索しましたが、ありませんでした。。。</p>
            @endif
            <div class="col-sm-2"></div>
        </div>
    @endsection
