@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    @switch($tab)
                        @case('wage-services')
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                            @break
                        @case('material-services')
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
                            @break
                    @endswitch
                </svg>
                Leistungen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
                <div class="menu border-right pr-3">
                    @can('viewAny', \App\Models\WageService::class)
                        <a class="menu-item @if (request()->is('wage-services')) active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('wage-services.index') }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                            </svg>
                            Lohndienstleistungen
                            <span class="ml-auto">{{ $wageServicesCount }}</span>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\MaterialService::class)
                        <a class="menu-item @if (request()->is('material-services')) active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('material-services.index') }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
                            </svg>
                            Materialleistungen
                            <span class="ml-auto">{{ $materialServicesCount }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="scroll-x border-bottom pb-1">
                    @can('viewAny', \App\Models\WageService::class)
                        <a class="menu-item @if (request()->is('wage-services')) active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('wage-services.index') }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                            </svg>
                            Lohndienstleistungen
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\MaterialService::class)
                        <a class="menu-item @if (request()->is('material-services')) active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('material-services.index') }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
                            </svg>
                            Materialleistungen
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
