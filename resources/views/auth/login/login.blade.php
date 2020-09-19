@extends('main')

@section('title', 'Codery | Autentificare')

@section('content')

    <div class="container-fluid">
        <div class="login-cont">
            <div class="row">
                <div class="mx-auto">
                    <h1 class="text-center">Bine ai revenit!</h1>
                </div>
            </div>
            <div class="row">
                <div class="mx-auto">
                    <div class="login-form">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <input class="form-control login_input {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" placeholder="Email..." value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="form-control login_input {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Parolă..." required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember">Păstrează-mă autentificat</label><br><br>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Conectează-te">
                        </form>
                    </div>
                </div>
            </div>
            <div class="mx-auto login-links">
                <div class="row">
                    <div class="col-sm-6 float-left text-center">
                        <p><a href="#">Crează cont</a></p>
                    </div>
                    <div class="col-sm-6 float-right text-center">
                        <p><a href="#">Ai uitat parola?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
