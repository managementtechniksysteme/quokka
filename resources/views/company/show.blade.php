@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('company.breadcrumb')

            <h3>
                Firma
                <small class="text-muted">{{ $company->full_name }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('companies.edit', $company) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Firma bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Firma zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            Stammblatt PDF erstellen
        </a>
        <form action="{{ route('companies.destroy', $company) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Firma entfernen
            </button>
        </form>

        <div class="dropdown d-inline d-lg-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownActionsButton" data-toggle="dropdown">
                Weitere Aktionen
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Firma zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    Stammblatt PDF erstellen
                </a>
                <form action="{{ route('companies.destroy', $company) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Firma entfernen
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
                <div class="menu border-right pr-3">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'overview']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                        </svg>
                        Stammdaten
                    </a>

                    <a class="menu-item @if (request()->tab == 'projects') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Projekte
                        <span class="ml-auto">{{ $company->projects_count }}</span>
                    </a>

                    <a class="menu-item @if (request()->tab == 'people') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Personen
                        <span class="ml-auto">{{ $company->people_count }}</span>
                    </a>
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="border-bottom pb-2">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'overview']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                        </svg>
                        Stammdaten
                    </a>

                    <a class="menu-item @if (request()->tab == 'projects') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Projekte
                    </a>

                    <a class="menu-item @if (request()->tab == 'people') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                        </svg>
                        Personen
                    </a>
                </div>
            </div>

            <div class="col-lg-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
