@extends('main')

@section('title', 'Codery | ' . $title)

@section('content')

    <div class="container">
        <h4 class="text-center" style="padding-top: 100px;">{{ $info }}</h4>
        @if ($subscribed)
            <h4 class="text-center">Daca dorești să te dezabonezi, te rog apasă <a href="{{ route('deleteNewsletter', $token) }}" style="cursor: pointer;"><u>aici</u></a>.</h4>
        @endif
    </div>

@endsection
