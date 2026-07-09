@extends('layouts.app')

@section('content')
    <div class="q-container" style="max-width: 540px;">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <div class="q-head-icon">
                    <svg class="icon-bs icon-20">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use>
                    </svg>
                </div>
                <h1 class="q-title">{{ __('Reauthentication') }}</h1>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('reauthenticate') }}" method="POST" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">
                    Anmeldung bestätigen
                    <div class="q-form-section__desc">
                        {{ __('The requested resource requires you to reauthenticate with your credentials due to security reasons.') }}
                        @if(auth()->user()->otp_secret)
                            {{ __('You can find your six digit one time password in the authenticator app on your mobile phone.') }}
                        @endif
                    </div>
                </div>
                <div class="q-form-section__body d-flex flex-column gap-3">
                    <div>
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password" autofocus>
                        <div class="invalid-feedback">
                            @error('password')
                                {{ $message }}
                            @else
                                {{ __('Please enter your password.') }}
                            @enderror
                        </div>
                    </div>

                    @if(auth()->user()->otp_secret)
                        <div>
                            <label for="one_time_password">{{ __('One Time Password') }}</label>
                            <input id="one_time_password" type="text" pattern="\d*" maxlength="6" class="form-control{{ $errors->has('one_time_password') ? ' is-invalid' : '' }}" name="one_time_password" required autocomplete="off">
                            <div class="invalid-feedback">
                                @error('one_time_password')
                                    {{ $message }}
                                @else
                                    {{ __('Please enter the six digit one time password from the authenticator app.') }}
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div>
                        <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                            {{ __('Authenticate') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
