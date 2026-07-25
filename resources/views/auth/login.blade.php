@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-1">Willkommen zurück</h1>
                <p class="q-subtitle mb-4">Melde dich an, um fortzufahren.</p>

                <form class="q-form needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username">{{ __('Username') }}</label>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        <div class="invalid-feedback">
                            @error('username')
                                {{ $message }}
                            @else
                                {{ __('Please enter your username.') }}
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <div class="invalid-feedback">
                            @error('password')
                                {{ $message }}
                            @else
                                {{ __('Please enter your password.') }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-in-right"></use></svg>
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="q-link--quiet" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
