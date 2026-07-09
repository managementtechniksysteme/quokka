@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20">
                        @switch($tab)
                            @case('wage-services')
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use>
                                @break
                            @case('material-services')
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use>
                                @break
                        @endswitch
                    </svg>
                </span>
                <div>
                    <h1 class="q-title">Leistungen</h1>
                    <div class="q-subtitle">Lohn- und Materialleistungen</div>
                </div>
            </div>

            @yield('head-action')
        </div>

        <div class="q-detail q-detail--aside-start">
            <aside>
                <nav class="q-subnav">
                    @can('viewAny', \App\Models\WageService::class)
                        <a class="q-subnav__item @if (request()->is('wage-services')) active @endif" href="{{ route('wage-services.index') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use></svg>
                            <span class="flex-grow-1">Lohndienstleistungen</span>
                            <span class="q-subnav__count">{{ $wageServicesCount }}</span>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\MaterialService::class)
                        <a class="q-subnav__item @if (request()->is('material-services')) active @endif" href="{{ route('material-services.index') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use></svg>
                            <span class="flex-grow-1">Materialleistungen</span>
                            <span class="q-subnav__count">{{ $materialServicesCount }}</span>
                        </a>
                    @endcan
                </nav>
            </aside>

            <div>
                @yield('tab')
            </div>
        </div>

    </div>
@endsection
