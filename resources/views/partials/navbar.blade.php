<nav class="navbar navbar-expand-lg navbar-light shadow sticky-top pt-3 pb-3" style="background-color: #FFFFFF;">
    <div class="container">
        <div class="navbar-brand img-container mb-2">
            <a href="/"><img src="{{asset('images/logo.svg')}}" alt="logo.png"></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Articole <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Despre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Donează</a>
                </li>
            </ul>
            @if (!isset($homePage))
                <form class="form-inline my-2 my-lg-0" method="GET" action="{{ route('acasa') }}">
                    @csrf
                    @method('GET')
                    <input class="form-control searchPlaceholder mr-sm-2" type="search" name="search" placeholder="Caută articol...">
                    <button class="btn searchButton my-2 my-sm-0" type="submit">Caută</button>
                </form>
            @endif
        </div>
    </div>
</nav>
