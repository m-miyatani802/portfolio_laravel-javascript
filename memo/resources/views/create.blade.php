@extends('layouts.app')

@section('title')
    単語登録
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h2>単語登録</h2>
            <form action="{{ route('words.wordStor') }}" method="get">
                @csrf
                @if (count($errors) > 0)
                    <p>えらー</p>
                @endif
                <div class="mb-3">
                    <label for="reading" class="form-label">読み方</label>
                    <input type="text" name="reading" class="form-control @error('reading') is-invalid @enderror"
                        id="reading">
                    <p style="color: red">※出来る限りひらがなでお願いします。</p>
                    @error('reading')
                        <div id="validateReading" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phrases" class="form-label">語句</label>
                    <input type="text" name="phrases" class="form-control @error('phrases') is-invalid @enderror"
                        id="phrases">
                </div>
                <div class="mb-3">
                    <label for="meaning" class="form-label">意味</label>
                    <input type="text" name="meaning" class="form-control @error('meaning') is-invalid @enderror"
                        id="meaning">
                </div>
                <div class="mb-3">
                    <label for="typing" class="form-label">タイピングスペル</label>
                    <input type="text" name="typing" class="form-control @error('typing') is-invalid @enderror"
                        id="typing">
                    <p style="color: red">※半角英数で入力ください</p>
                    @error('typing')
                        <div id="validateTyping" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <span class="btn-group">
                    <button type="submit" class="btn btn-primary">登録</button>
                    <button class="btn btn-primary" onClick="history.back(); return false;">戻る</button>
                </span>
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>
@endsection
