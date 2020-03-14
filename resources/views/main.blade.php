<!DOCTYPE html>

<html lang="en">

@include('partials.head')

<body>

<div id="app">

    @yield('partials.navbar')

    @yield('content')

    @include('partials.scripts')

    @include('partials.footer')

</div>

<script src="{{ asset('/js/app.js') }}"></script>

@yield('scripts')

</body>

</html>