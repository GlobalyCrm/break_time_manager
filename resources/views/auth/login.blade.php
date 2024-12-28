@extends('layouts.auth')
@section('content')
<div class="welcome welcome_login">
    <div class="welcome_header text-center">
        <img class="welcome-header_img" src="img/break.jpg" alt="">
        <p class="welcome-header_title">С возвращением!</p>
    </div>
    <form class="welcome-header_content"  method="POST" action="{{ route('login') }}">
        @csrf
        @method('POST')
        <input class="welcome-header-content_input @error('email') is-invalid @enderror mb-3" name="email" value="{{ old('email') }}" type="email" placeholder="Логин" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <input class="welcome-header-content_input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" type="password" placeholder="Пароль">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="welcome-header-content-link welcome-header-content-link_login">
{{--            <a class="welcome-header-content_button welcome-header-content-button_register text-center" href="register.html">Регистрация</a>--}}
            <button type="submit" class="welcome-header-content_button welcome-header-content-button_login text-center">Вход</button>
        </div>
        <div class="d-flex justify-content-between mt-2">
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ translate_title('Forgot Your Password?') }}
                </a>
            @endif
                <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ translate_title('Remember Me') }}
                </label>
            </div>
        </div>
    </form>
</div>
@endsection
