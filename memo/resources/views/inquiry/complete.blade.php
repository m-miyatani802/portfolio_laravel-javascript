@extends('layouts.app')

@section('title')
    マイページ
@endsection

@section('content')
<div class="row-my-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">感謝！</div>
            <div class="card-body">
                <h4>ご意見ありがとうございました</h4>
                <p>引き続きご利用ください！！</p>
            </div>
            <span class="btn-group">
                <button class="btn btn-primary" onClick="history.back(); return false;">戻る</button>
            </span>
        </div>
        <div class="col-sm-2"></div>
    </div>

@endsection
