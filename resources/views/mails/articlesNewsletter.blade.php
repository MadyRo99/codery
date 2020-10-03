@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')

    <div class="content-text">
        <tr>
            <td width="100%" height="20"></td>
        </tr>
        <tr>
            <td class="title">
                <h1>{{ $title }}</h1>
            </td>
        </tr>
    </div>

    <tr>
        <td width="100%" height="25"></td>
    </tr>

    <tr>
        <td class="paragraph">
            <p>{{ $content }}</p>
        </td>
    </tr>

    <tr>
        <td width="100%" height="25"></td>
    </tr>

    @if(count($articles))
        @foreach($articles as $article)
            <tr>
                <td class="paragraph">
                    <h2><a href="http://localhost:8000/article/{{ $article->slug }}">{{ $article->title }}</a></h2>
                    @if($article->main_image)
                        <a href="http://localhost:8000/article/{{ $article->slug }}">
                            <div class="img-container">
                                <img src="http://localhost:8000/storage/articles/{{ $article->slug }}/{{ $article->main_image }}" alt="article_main_image">
                            </div>
                        </a>
                    @endif
                    <p>{{ $article->description }}</p>
                </td>
            </tr>
        @endforeach

        <tr>
            <td width="100%" height="25"></td>
        </tr>
    @endif

    <tr>
        <td class="paragraph bottom-notice">
            <p>Apasă <a href="http://localhost:8000/deleteNewsletter/{{ $token }}" class="unsubscribe">aici</a> dacă dorești să te dezabonezi de la Newsletter.</p>
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop
