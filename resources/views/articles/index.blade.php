@extends('main')

@section('title', 'Codery | Articol')

@section('content')

    <div>
        <article-view slug="{{ $slug }}"></article-view>
    </div>

@endsection