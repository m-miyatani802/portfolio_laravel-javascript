@extends('layouts.app')

@section('title')
    トップページ
@endsection

@section('style')
    <style>
        . {
            margin-right: 50;
        }

        .form-check-input {
            display: :block;
        }

        .pagination {
            font-size: 10pt;
        }

        .pagination li {
            display: inline-block
        }

        tr th a:link {
            color: blueviolet;
        }

        tr th a:visited {
            color: red;
        }

        tr th a:hover {
            color: green;
        }

        tr th a:active {
            color: chartreuse;
        }
    </style>
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h1>トップページ</h1>
            <h2>~単語一覧~</h2>
            <a href="{{ route('typ.top') }}">タイピング練習</a>
            <table class="table table-striped table-hover">
                <tr>
                    <th><a href="{{ route('top.index') }}?sort=created_at">最新</a></th>
                    <th><a href="{{ route('top.index') }}?sort=reading">読み方</a></th>
                    <th><a href="{{ route('top.index') }}?sort=phrases">単語</a></th>
                    <th><a href="{{ route('top.index') }}?sort=meaning">意味</a></th>
                    <th>登録者</th>
                </tr>
                @foreach ($items['words'] as $item)
                    <tr>
                        <td>　　　</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->reading, 30, '...') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->phrases, 30, '...') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->meaning, 45, '...') }}</td>
                        {{-- <td>{{ $item->user->name }}</td> --}}
                        <td><a
                                href="{{ Route('other_user.page') }}?user_id={{ $item->user_id }}&user_name={{ $item->user->name }}">{{ $item->user->name }}</a>
                        </td>
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
                                    <form action="{{ route('mylist.create') }}">
                                        @csrf
                                        <input type="submit" class="btn btn-primary mx-sm-1" value="マイリスト作成">
                                    </form>
                                @elseif(!empty($items['user_id']))
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            マイリスト
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @foreach ($items['mylists'] as $mylist)
                                                @if (count($items['mylists']) === 0)
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            無し
                                                        </a>
                                                    </li>
                                                @elseif(count($items['mylists']) > 0)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ Route('mylist.register') }}?word_id={{ $item->id }}&mylist_id={{ $mylist->id }}">
                                                            {{ \Illuminate\Support\Str::limit($mylist->name, 20, '...') }}に登録
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
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

            @if (empty($sort))
                {{ $items['words']->links() }}
            @elseif(!empty($sort))
                {{ $items['words']->appends(['sort' => $sort])->links() }}
            @endif

        </div>
        <div class="col-sm-2"></div>
    </div>
@endsection
