@extends('main')

@section('title', 'Codery | Editeaza Articol')

@section('content')

    <div>
        <edit-article slug="{{ $slug }}"></edit-article>
    </div>

@endsection