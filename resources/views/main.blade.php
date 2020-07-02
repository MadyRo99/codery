<!DOCTYPE html>

<html lang="en">

@include('partials.head')

<body>

<div id="app">

    <div class="app-content">
        @include('partials.navbar')

        @yield('content')
    </div>

    @include('partials.scripts')

    @include('partials.footer')

</div>

<script src="{{ asset('/js/app.js') }}"></script>

@yield('scripts')

</body>

</html>
