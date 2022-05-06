@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @error('username')
                        <notification type="danger" v-cloak>
                            <div class="d-inline-flex align-items-center">
                                <svg class="icon icon-24 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-octagon"></use>
                                </svg>
                                {{ $message }}
                            </div>
                        </notification>

                    @enderror

                    @if (session('status'))
                        <notification type="info" v-cloak>
                            <div class="d-inline-flex align-items-center">
                                <svg class="icon icon-24 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                </svg>
                                {{ session('status') }}
                            </div>
                        </notification>

                    @endif

                    <form class="needs-validation" method="POST" action="{{ route('password.email') }}" novalidate>
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                <div class="invalid-feedback">
                                    @error('username')
                                        {{ $message }}
                                    @else
                                        Bitte gib deinen Benutzername ein.
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
