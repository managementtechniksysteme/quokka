@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-1">{{ __('Two Factor Authentication') }}</h1>

                <div class="q-banner q-banner--info mt-3 mb-4">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                    <div>{{ __('You can find your six digit one time password in the authenticator app on your mobile phone.') }}</div>
                </div>

                <form class="q-form needs-validation" action="{{ $submitUrl }}" method="POST" novalidate>
                    @csrf

                    <input type="hidden" name="user" value="{{ $user }}">

                    <div class="mb-4">
                        <label for="one_time_password">{{ __('One Time Password') }}</label>
                        <input id="one_time_password" type="text" pattern="\d*" maxlength="6" class="form-control{{ $errors->has('one_time_password') ? ' is-invalid' : '' }}" name="one_time_password" required autocomplete="off" autofocus>
                        <div class="invalid-feedback">
                            @error('one_time_password')
                                {{ $message }}
                            @else
                                {{ __('Please enter the six digit one time password from the authenticator app.') }}
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-in-right"></use></svg>
                        {{ __('Login') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
