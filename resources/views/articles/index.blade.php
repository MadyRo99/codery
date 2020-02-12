@extends('main')

@section('title', 'Codery | Articol')

@section('content')

    <div class="container article">
        <header class="article-header">
            <div class="row article-title">
                <div class="col-12">
                    <h1 class="text-center">Understand Routing in Vue.js With Examples</h1>
                </div>
            </div>
            <div class="row article-header-details">
                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <div class="author-image">
                            <a href="#"><img src="images/madalin.png" alt="madalin.png" class="rounded-circle"></a>
                        </div>
                        <div class="author-details pl-2 align-self-center">
                            <a href="#"><p class="author-name mb-1">@MadalinDanescu</p></a>
                            <p class="article-info text-secondary mb-0">Ian 27, 2020 | 12 min</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-end">
                        <span class="category-link py-2"><i class="fas fa-tags fa-md"></i> <a href="#">JavaScript</a></span>
                        <div class="article-social-share pl-2">
                            <div>
                                <a href="https://www.facebook.com/"><i class="fab fa-facebook-square fa-lg pr-1"></i></a>
                                <a href="https://www.facebook.com/"><i class="fab fa-twitter-square fa-lg"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="article-body">
            <section class="article-image-container">
                <img src="images/coding.jpg" alt="coding.jpg" class="article-image img-fluid mx-auto d-block">
                <p>
                    Curabitur ac lorem quis neque venenatis aliquam. Quisque scelerisque in neque egestas egestas. Maecenas ipsum orci, dapibus eu bibendum sit amet, tincidunt ac tortor. Pellentesque bibendum risus id suscipit varius. Nulla et cursus nisl. Aenean tincidunt ornare placerat. Curabitur et tellus nec mi scelerisque volutpat. Maecenas eros justo, dignissim a tempor id, iaculis sed lorem. Curabitur vitae hendrerit nisi, eu ultrices turpis. Integer pellentesque ipsum in elit venenatis bibendum. Aenean eu urna placerat libero tincidunt ultricies. Fusce dui metus, malesuada at scelerisque ut, posuere vel risus. Vivamus feugiat justo velit, ac egestas libero dictum quis.
                </p>
                <p>
                    Nullam porta odio nec maximus tempus. Aenean porta, leo at elementum porttitor, urna metus commodo lorem, in varius lorem tortor eu tellus. Vivamus tempor quam metus, eget suscipit ipsum lacinia a. Pellentesque placerat risus eget augue sollicitudin, vel egestas est consequat. Quisque scelerisque lacus blandit, aliquet dui vitae, consequat urna. Nulla ultrices orci sit amet tristique blandit. Morbi eu purus sollicitudin, fermentum risus ac, sodales velit. Phasellus lobortis egestas nulla id suscipit. Aliquam mi neque, luctus et congue vel, varius nec quam.
                </p>
                <p>
                    Duis ligula massa, tincidunt vel purus ut, posuere bibendum metus. Donec a auctor nisl, at cursus justo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam a facilisis nisi, eget porttitor est. Cras commodo turpis et tempor pharetra. Morbi nisl magna, laoreet ut ultricies vitae, aliquet quis velit. Proin ultricies erat nec porttitor posuere. Praesent facilisis pulvinar elit. Curabitur posuere est sit amet molestie suscipit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus ullamcorper ultricies. Quisque scelerisque dolor massa, et bibendum urna consequat a. Donec condimentum sed ante nec mattis. Suspendisse placerat leo libero, a volutpat odio varius eu.
                </p>
            </section>
        </div>
    </div>

@endsection