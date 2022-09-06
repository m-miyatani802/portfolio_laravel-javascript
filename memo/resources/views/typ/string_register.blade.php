@extends('layouts.new')

@section('title')
    トップページ
@endsection

@section('content')
<div class="row-my-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">単語編集</div>
            <div class="card-body">
                <form action="{{ route('typ_string.register_action') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="typingCharacter" class="form-label">タイピング文字</label>
                        <input type="text" name="typingCharacter" class="form-control" id="typingcharacter">
                        <input type="hidden" name="word_id" value="{{ $id }}">
                    </div>
                    <button type="submit" class="btn btn-primary">登録</button>
                </form>
                <form action="#">
                    <button class="btn btn-outline-primary" onClick="history.back(); return false;">戻る</button>
                </form>

            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>

@endsection