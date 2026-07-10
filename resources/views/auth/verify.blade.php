@extends('layouts.app')

@section('content')
    <div class="q-container d-flex justify-content-center" style="max-width: none; padding-top: 3rem;">
        <div class="q-card" style="max-width: 460px; width: 100%;">
            <div class="q-card__body p-4 p-md-5">
                <h1 class="q-title mb-3">{{ __('Verify Your Email Address') }}</h1>

                @if (session('resent'))
                    <div class="q-banner q-banner--success">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check-circle"></use></svg>
                        <div>{{ __('A fresh verification link has been sent to your email address.') }}</div>
                    </div>
                @endif

                <p class="q-subtitle mb-0">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}" class="q-link--quiet">{{ __('click here to request another') }}</a>.
                </p>
            </div>
        </div>
    </div>
@endsection
