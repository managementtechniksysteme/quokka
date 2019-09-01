@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reauthentication') }}</div>

                    <div class="card-body">

                        @if(auth()->user()->otp_secret)
                        <p class="alert alert-info mb-4 d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                            </svg>
                            {{ __('You can find your six digit one time password in the authenticator app on your mobile phone.') }}
                        </p>
                        @endif

                            <p class="text-muted mb-4">
                                {{ __('The requested resource requires you to reauthenticate with your credentials due to security reasons.') }}
                            </p>

                        <form class="needs-validation" action="{{ route('reauthenticate') }}" method="POST" novalidate>
                            @csrf

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">

                                    <div class="invalid-feedback">
                                        @error('password')
                                            {{ $message }}
                                        @else
                                            {{ __('Please enter your password.') }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->otp_secret)
                                <div class="form-group row">
                                    <label for="one_time_password" class="col-md-4 col-form-label text-md-right">{{ __('One Time Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="one_time_password" type="text" pattern="\d*" maxlength="6" class="form-control{{ $errors->has('one_time_password') ? ' is-invalid' : '' }}" name="one_time_password" required autocomplete="off" autofocus>

                                        <div class="invalid-feedback">
                                            @error('one_time_password')
                                                {{ $message }}
                                            @else
                                                {{ __('Please enter the six digit one time password from the authenticator app.') }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                        <svg class="feather feather-16 mr-2">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                                        </svg>
                                        {{ __('Authenticate') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection