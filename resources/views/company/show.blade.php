@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('company.breadcrumb')

            <h3>
                Firma
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $company->full_name }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $company)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('companies.edit', $company) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $company)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $company)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $company)
                    <form action="{{ route('companies.destroy', $company) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">

        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
                <div class="menu border-right pr-3">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'overview']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                        </svg>
                        Stammdaten
                    </a>

                    @can('viewAny', \App\Models\Project::class)
                        <a class="menu-item @if (request()->tab == 'projects') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            Projekte
                            <span class="ml-auto">{{ $company->projects_count > 0 ? $company->projects_count : '' }}</span>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Person::class)
                        <a class="menu-item @if (request()->tab == 'people') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                            </svg>
                            Personen
                            <span class="ml-auto">{{ $company->people_count >0 ? $company->people_count : '' }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="border-bottom pb-2">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'overview']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                        </svg>
                        Stammdaten
                    </a>

                    @can('viewAny', \App\Models\Project::class)
                        <a class="menu-item @if (request()->tab == 'projects') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'projects']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            Projekte
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Person::class)
                        <a class="menu-item @if (request()->tab == 'people') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('companies.show', [$company, 'tab' => 'people']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                            </svg>
                            Personen
                        </a>
                    @endcan
                </div>
            </div>

            <div class="col-lg-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
