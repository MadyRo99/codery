@extends('main')

@section('title', 'Codery | ' . $title)

@section('description', $description)

@section('image', $image)

@section('slug', $slug)

@section('type', 'article')

@section('content')

    <div>
        <article-view slug="{{ $slug }}"></article-view>
    </div>

@endsection
