<footer class="article-footer">
    <div class="row social-icons justify-content-center">
        <div class="social-icon img-container">
            <a href="#"><img src="{{asset('images/facebook_logo.png')}}" alt="facebook.png"></a>
        </div>
        <div class="social-icon img-container">
            <a href="#"><img src="{{asset('images/instagram_logo.png')}}" alt="instagram.png"></a>
        </div>
    </div>
    <div class="legal-info text-center">
        <div class="row justify-content-center">
            <p class="float-left"><a href="{{ route('terms') }}">Termeni și condiții de utilizare</a></p>
            <p class="separator px-2 d-none d-md-block">|</p>
            <p class="float-left"><a href="{{ route('privacy') }}">Politică de confidențialitate</a></p>
            <p class="separator px-2 d-none d-md-block">|</p>
            <p class="float-left"><a href="{{ route('cookies') }}">Politică privind cookie-urile</a></p>
        </div>
        <p>Contact: <a href="mailto:coderyromania@gmail.com">coderyromania@gmail.com</a></p>
        <p class="copyright mb-0">© 2020 <a href="#">Codery.Ro</a></p>
    </div>
</footer>
