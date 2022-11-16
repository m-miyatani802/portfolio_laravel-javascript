@extends('layouts.app')
@section('title')
    単語詳細
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <?php if (!empty($_SESSION['msg'])) : ?>
            <div class="row-my-2">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 alert-success alert-dismissble fade show">
                    <?= $_SESSION['msg'] ?>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <?php endif ?>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">詳細</div>
                <div class="card-body">
                    <div class="mb-3">
                        {{-- <label for="reading" class="form-label">読み</label> --}}
                        <h3>読み方</h3>
                        <p>
                            @if (!$item['word']->reading == null)
                                {{ $item['word']->reading }}
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        {{-- <label for="phrases" class="form-label">語句</label> --}}
                        <h3>語句</h3>
                        <p>
                            @if (!$item['word']->phrases == null)
                                {{ $item['word']->phrases }}
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        {{-- <label for="meaning" class="form-label">意味</label> --}}
                        <h3>意味</h3>
                        <p>
                            @if (!$item['word']->meaning == null)
                                {{ $item['word']->meaning }}
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label for="meaning" class="form-label">タイピングスペル</label>
                        <p>
                            @if (!$item['word']->typing == null || !empty($item['word']->typing))
                                {{ $item['word']->typing }}
                            @endif
                        </p>
                    </div>
                    @if ($item['user_id'] == $item['word']->user_id)
                        <form action="{{ route('words.edit', $item['word']->id) }}" method="get">
                            @csrf
                            <input type="submit" class="btn btn-primary mx-sm-1" value="編集">
                        </form>
                    @else
                    @endif
                    <div class="mb-3">
                        <label for="user_register" class="form-label">登録者</label>
                        <a
                            href="{{ Route('other_user.page') }}?user_id={{ $item['word']->user_id }}&user_name={{ $item['word']->user->name }}">{{ $item['word']->user->name }}</a>
                    </div>
                    <div class="mb-3">
                        <form action="{{ route('favorite.users_register') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $item['word']->user_id }}">
                            <input type="submit" class="btn btn-primary mx-sm-1" value="ユーザーお気に入り">
                        </form>
                    </div>

                    @if ((Auth::id() == $item['word']->user_id) === true)
                        {{-- <a href="{{ route('words.edit') }}?id={{ $item['word']->id }}">タイピング文字登録</a> --}}
                    @endif
                    <div class="mb-3">
                        <form action="{{ route('favorite.word_register') }}" method="post">
                            @csrf
                            <input type="hidden" name="word_id" value="{{ $item['word']->id }}">
                            <input type="submit" class="btn btn-primary mx-sm-1" value="お気に入り">
                        </form>
                    </div>

                    <div>
                        @if (!empty($item['user_id']) && empty($item['mylists']))
                            <input type="submit" class="btn btn-primary mx-sm-1" value="マイリスト登録">
                        @elseif(!empty($item['mylists']))
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    mylists
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @foreach ($item['mylists'] as $mylist)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ Route('mylist.register') }}?word_id={{ $item['word']->id }}&mylist_id={{ $mylist->id }}">
                                                {{ $mylist->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    @endsection
