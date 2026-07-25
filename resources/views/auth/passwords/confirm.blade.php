@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-1">{{ __('Confirm Password') }}</h1>
                <p class="q-subtitle mb-4">{{ __('Please confirm your password before continuing.') }}</p>

                <form class="q-form" method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary text-white w-100">
                        {{ __('Confirm Password') }}
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
