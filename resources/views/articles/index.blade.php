@extends('main')

@section('title', 'Codery | ' . $title)

@section('content')

    <div>
        <article-view slug="{{ $slug }}"></article-view>
    </div>

@endsection