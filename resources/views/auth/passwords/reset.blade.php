@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-1">{{ __('Reset Password') }}</h1>
                <p class="q-subtitle mb-4">Wähle ein neues Passwort für dein Konto.</p>

                <form class="q-form needs-validation" method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="username">{{ __('Username') }}</label>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $username ?? old('username') }}" required autocomplete="username" autofocus>
                        <div class="invalid-feedback">
                            @error('username')
                                {{ $message }}
                            @else
                                Bitte gib deinen Benutzername ein.
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <div class="invalid-feedback">
                            @error('password')
                                {{ $message }}
                            @else
                                Bitte gib dein neues Passwort ein.
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary text-white w-100">
                        {{ __('Reset Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
