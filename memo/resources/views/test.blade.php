@extends('layouts.new')

@section('title')
    トップページ
@endsection

@section('content')
    <div>
        @foreach ($items as $item)
            <p>{{ $item->reading }}</p>

            @if (!$item->typing == null)
                <p>{{ $item->typing->typing_character }}</p>
            @endif
        @endforeach
    </div>
@endsection
