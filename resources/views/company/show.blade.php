@extends('layouts.app')

@section('content')
    <div class="q-container">

        <nav class="q-breadcrumb">
            <a href="{{ route('companies.index') }}">Firmen</a>
            <span class="q-breadcrumb__sep">/</span>
            <span>{{ $company->full_name }}</span>
        </nav>

        <div class="q-page-head">
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

        <div class="q-detail q-detail--aside-start mt-4 pt-2">
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
