<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta property="og:title" content="@yield('title')" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(View::hasSection('type'))
        <meta property="og:type" content="@yield('type')" />
    @else
        <meta property="og:type" content="website" />
    @endif
    @if(View::hasSection('description'))
        <meta name="description" content="@yield('description')">
    @else
        <meta name="description" content="Codery Romania este un blog destinat celor care profesează sau sunt pasionați de domeniul IT, ce își propune prin postarea unor articole concise, ce conțin informații corecte și bine verificate, să ajute la dezvoltarea unei comunității bine informate în acest domeniu ce poate oricând să se educe, gratis, prin conținutul oferit.">
    @endif
    @if(View::hasSection('slug'))
        <meta property="og:image" content="../storage/articles/@yield('slug')/@yield('image')" />
        <meta property="og:url" content="https://codery.ro/article/@yield('slug')" />
    @else
        <meta property="og:image" content="{{asset('/images/share_logo.png')}}" />
    @endif
    <meta name="keywords" content="CODERY, ROMANIA, BLOG, IT, WEB, HTML, CSS, JAVASCRIPT, JAVA, PYTHON, AI, IOT, ANDROID, IOS, MOBILE, PHP, SQL, LARAVEL, BOOTSTRAP, PROGRAMARE">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/cookiealert.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
