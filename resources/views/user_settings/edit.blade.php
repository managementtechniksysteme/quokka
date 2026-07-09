@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <div class="q-head-icon">
                    <svg class="icon-bs icon-20">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                    </svg>
                </div>
                <h1 class="q-title">Einstellungen</h1>
            </div>
        </div>

        <div class="q-detail q-detail--aside-start mt-4">
            <aside>
                <nav class="q-subnav">
                    <a class="q-subnav__item @if(!request()->tab || request()->tab == 'general') active @endif"
                       href="{{ route('user-settings.edit', ['tab' => 'general']) }}">
                        <svg class="icon-bs icon-16">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                        </svg>
                        Allgemeines
                        @unless(Auth::user()->signature())
                            <svg class="icon-bs icon-14 ms-auto" style="color: var(--q-red)">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
                            </svg>
                        @endunless
                    </a>
                    <a class="q-subnav__item @if(request()->tab == 'interface') active @endif"
                       href="{{ route('user-settings.edit', ['tab' => 'interface']) }}">
                        <svg class="icon-bs icon-16">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#columns"></use>
                        </svg>
                        Darstellung
                    </a>
                    <a class="q-subnav__item @if(request()->tab == 'notifications') active @endif"
                       href="{{ route('user-settings.edit', ['tab' => 'notifications']) }}">
                        <svg class="icon-bs icon-16">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use>
                        </svg>
                        Benachrichtigungen
                    </a>
                    <a class="q-subnav__item @if(request()->tab == 'security') active @endif"
                       href="{{ route('user-settings.edit', ['tab' => 'security']) }}">
                        <svg class="icon-bs icon-16">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#shield"></use>
                        </svg>
                        Sicherheit
                    </a>
                </nav>
            </aside>

            <div>
                @yield('tab')
            </div>
        </div>
    </div>
@endsection
