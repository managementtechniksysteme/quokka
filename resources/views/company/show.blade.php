@extends('layouts.app')

{{-- Mobile app bar: back chevron + company name + kebab, replacing the
     standard badge/title/search/bell (see partials/navbar.blade.php) — per
     the Claude Design mobile mockup's "Detail" frame (2026-07-21). Same
     actions as the desktop kebab below (+ Bearbeiten, which desktop shows as
     its own separate button since there's room there but not here). --}}
@section('mobile-detail-bar')
    <a href="{{ route('companies.index') }}" class="q-appbar__btn" aria-label="Zurück zu Firmen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $company->full_name }}</span>
    <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#companyShowActionsSheet" aria-controls="companyShowActionsSheet" aria-label="Aktionen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
    </button>
@endsection

{{-- Separate from mobile-detail-bar above: this sheet must NOT be nested
     inside .q-appbar (position:fixed, z-index:1020) or its lowest rows
     render behind .q-tabbar (z-index:1030) and become untappable (2026-07-21,
     user: "the remove button hidden by the nav bar") — navbar.blade.php
     yields this at the top level, alongside the Mehr sheet. --}}
@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="companyShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('update', $company)
                <a class="q-row" href="{{ route('companies.edit', $company) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('email', $company)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $company)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $company)
                <form action="{{ route('companies.destroy', $company) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        {{-- Desktop only: the mobile app bar's back button already covers
             this, and the record name lives there too. --}}
        <nav class="q-breadcrumb d-none d-md-flex">
            <a href="{{ route('companies.index') }}">Firmen</a>
            <span class="q-breadcrumb__sep">/</span>
            <span>{{ $company->full_name }}</span>
        </nav>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar q-avatar--{{ $company->avatar_colour }}">{{ $company->initials }}</span>
                <div>
                    <div class="q-eyebrow">Firma</div>
                    <h1 class="q-title">{{ $company->full_name }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $company)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('companies.edit', $company) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="companyShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="companyShowDropdown">
                        @can('email', $company)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $company)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $company)
                            <form action="{{ route('companies.destroy', $company) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile: less top margin than desktop — there's no breadcrumb/
             page-head above it here (both d-none below md), so the same
             mt-4 desktop uses to clear those left an oversized gap under
             the app bar (2026-07-21, user: "the nav is a bit far down"). --}}
        <div class="q-detail q-detail--aside-start mt-2 pt-1 mt-md-4 pt-md-2">
            <aside>
                <nav class="q-subnav">
                    <a class="q-subnav__item @if (request()->tab == 'overview' || !request()->tab) active @endif" href="{{ route('companies.show', [$company, 'tab' => 'overview']) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                        <span class="flex-grow-1">Stammdaten</span>
                    </a>

                    @can('viewAny', \App\Models\Project::class)
                        <a class="q-subnav__item @if (request()->tab == 'projects') active @endif" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                            <span class="flex-grow-1">Projekte</span>
                            @if($company->projects_count > 0)<span class="q-subnav__count">{{ $company->projects_count }}</span>@endif
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Person::class)
                        <a class="q-subnav__item @if (request()->tab == 'people') active @endif" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                            <span class="flex-grow-1">Personen</span>
                            @if($company->people_count > 0)<span class="q-subnav__count">{{ $company->people_count }}</span>@endif
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
