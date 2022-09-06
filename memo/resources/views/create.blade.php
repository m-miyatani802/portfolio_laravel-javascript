@extends('layouts.new')

@section('title')
    単語登録
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <form action="{{ route('words.wordStor') }}" method="get">
                @csrf
                @if (count($errors) > 0)
                    <p>えらー</p>
                @endif
                <div class="mb-3">
                    <label for="reading" class="form-label">reading</label>
                    <input type="text" name="reading" class="form-control @error('reading') is-invalid @enderror"
                        id="reading">
                    @error('reading')
                        <div id="validateReading" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phrases" class="form-label">phrases</label>
                    <input type="text" name="phrases" class="form-control @error('phrases') is-invalid @enderror" id="phrases">
                </div>
                <div class="mb-3">
                    <label for="meaning" class="form-label">meaning</label>
                    <input type="text" name="meaning" class="form-control @error('meaning') is-invalid @enderror" id="meaning">
                </div>
                <div class="mb-3">
                    <label for="typing" class="form-label">タイピングスペル</label>
                    <input type="text" name="typing" class="form-control @error('typing') is-invalid @enderror" id="typing">
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
