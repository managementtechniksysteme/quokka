@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-1">{{ __('Reset Password') }}</h1>
                <p class="q-subtitle mb-4">Gib deinen Benutzernamen ein, um dein Passwort zurückzusetzen.</p>

                @error('username')
                    <notification type="danger" v-cloak>
                        <div class="d-inline-flex align-items-center">
                            <svg class="icon-bs icon-24 me-2">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-octagon"></use>
                            </svg>
                            {{ $message }}
                        </div>
                    </notification>
                @enderror

                @if (session('status'))
                    <notification type="info" v-cloak>
                        <div class="d-inline-flex align-items-center">
                            <svg class="icon-bs icon-24 me-2">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use>
                            </svg>
                            {{ session('status') }}
                        </div>
                    </notification>
                @endif

                <form class="q-form needs-validation" method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="username">{{ __('Username') }}</label>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        <div class="invalid-feedback">
                            @error('username')
                                {{ $message }}
                            @else
                                Bitte gib deinen Benutzername ein.
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                        {{ __('Send Password Reset Link') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
