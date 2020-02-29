@extends('main')

@section('title', 'Codery | Inregistrare')

@section('content')

    <div class="container-fluid">
        <div class="login-cont">
            <div class="row">
                <div class="mx-auto">
                    <h1 class="text-center">Bine ai venit!</h1>
                </div>
            </div>
            <div class="row">
                <div class="mx-auto">
                    <div class="login-form">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="form-group">
                                        <input class="form-control login-input {{ $errors->has('username') ? ' is-invalid' : '' }}" type="text" name="username" placeholder="Nume de utilizator..." value="{{ old('username') }}" required autofocus>
                                        @if ($errors->has('username'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control login-input {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" placeholder="Email..." value="{{ old('email') }}" required>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control login-input {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Parolă..." required>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input id="password-confirm" type="password" class="form-control login-input" name="password_confirmation" placeholder="Confirmare parolă..." required>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Inregistrează-te">
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 text-right d-none d-md-block d-lg-block"><img src="images/privacy.png" alt="privacy.png"></div>
                                    <div class="col-md-6 text-center d-none d-md-block d-lg-block"><img src="images/cookies.png" alt="cookies.png"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Dacă te înscrii, îţi exprimi acordul cu <a href="#"><u>Condiţiile de utilizare</u></a>. Din <a href="#"><u>Politica de utilizare a datelor</u></a> poţi afla cum colectăm, folosim şi distribuim datele tale, iar din <a href="#"><u>Politica de utilizare a modulelor cookie</u></a> poţi afla cum utilizăm modulele cookie şi tehnologii similare.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-auto login-links">
                <div class="row">
                    <div class="col-sm-6 float-left text-center">
                        <p><a href="{{ route('login') }}">Conectare</a></p>
                    </div>
                    <div class="col-sm-6 float-right text-center">
                        <p><a href="{{ route('password.reset') }}">Ai uitat parola?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection