@extends('layouts.app')
@section('title')
    お問い合わせ
@endsection

@section('content')
    <div class="row-my-2">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">ご意見</div>
                <div class="card-body">
                    <form action="{{ route('inquiry.send') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">お名前</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name">
                            @error('name')
                                <div id="validateTyping" class="invalid-feedback">
                                    <p>{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">メールアドレス</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email">
                        </div>
                        {{-- <div class="mb-3">
                            <label for="contact_type" class="form-label">お問い合わせの種類</label>
                            <input type="text" name="contact_type" class="form-control @error('contact_type') is-invalid @enderror"
                                id="contact_type">
                        </div> --}}
                        <div class="mb-3">
                            <label for="inquiry" class="form-label">ご意見</label>
                            <textarea class="form-control @error('inquiry') is-invalid @enderror" name="inquiry" id="inquiry" cols="30" rows="10"></textarea>
                            @error('inquiry')
                                <div id="validateTyping" class="invalid-feedback">
                                    <p>{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <span class="btn-group">
                            <button type="submit" class="btn btn-primary">送信</button>
                            <button class="btn btn-primary" onClick="history.back(); return false;">戻る</button>
                        </span>
                                </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    @endsection
