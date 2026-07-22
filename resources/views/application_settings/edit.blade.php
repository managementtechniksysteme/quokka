@extends('layouts.app')

@section('content')
    <div class="q-container">
        {{-- Mobile: the app bar already carries "Einstellungen" + its own
             gear icon (partials/navbar.blade.php's $mobilePageLabels) —
             same "double headers" fix as search/notification/qr-scan/etc,
             desktop-only here (2026-07-22). --}}
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <div class="q-head-icon">
                    <svg class="icon-bs icon-20">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                    </svg>
                </div>
                <h1 class="q-title">{{ config('app.name') }} Einstellungen</h1>
            </div>
        </div>

        <div class="q-detail q-detail--aside-start mt-2 mt-md-4">
            <aside>
                <nav class="q-subnav">
                    <a class="q-subnav__item @if(!request()->tab || request()->tab == 'general') active @endif"
                       href="{{ route('application-settings.edit', ['tab' => 'general']) }}">
                        <svg class="icon-bs icon-16">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                        </svg>
                        Allgemeines
                    </a>
                </nav>
            </aside>

            <div>
                @yield('tab')
            </div>
        </div>
    </div>
@endsection
