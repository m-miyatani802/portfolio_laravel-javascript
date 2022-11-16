@extends('layouts.app')

@section('title')
    お気に入りユーザー
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h1>お気に入り</h1>
            <h2>~ユーザー一覧~</h2>
            @if (count($items) > 0)
                <table class="table table-striped table-hover">
                    <tr>
                        <th>USER名</th>
                    </tr>
                    @foreach ($items as $item)
                        <tr>
                            <td><a
                                    href="{{ Route('other_user.page') }}?user_id={{ $item->other_user_id }}&user_name={{ $item->name }}">{{ $item->name }}</a>
                            </td>
                            <td>
                                <form action="{{ Route('favorite.user_delete') }}" method="post">
                                    @csrf
                                    <input type="submit" class="btn btn-primary mx-sm-1" value="お気に入りから外す">
                                    <input type="hidden" name="other_user" value="{{ $item->favorites_other_user_id }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <p>お気に入りはありません</p>
            @endif
            {{ $items->links() }}
        </div>
        <div class="col-sm-2"></div>
    </div>
@endsection
