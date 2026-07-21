@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('vehicles.index') }}" class="q-appbar__btn" aria-label="Zurück zu Fahrzeugen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $vehicle->registration_identifier }}</span>
    @canany(['update', 'delete'], $vehicle)
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#vehicleShowActionsSheet" aria-controls="vehicleShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcanany
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="vehicleShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('update', $vehicle)
                <a class="q-row" href="{{ route('vehicles.edit', $vehicle) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('email', $vehicle)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $vehicle)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $vehicle)
                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="post">
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

        <div class="d-none d-md-block">
            @include('vehicle.breadcrumb')
        </div>

        @if($vehicle->private)
            <div class="q-meta d-flex d-md-none mt-2 pt-1 mb-3">
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                    privat
                </span>
            </div>
        @endif

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <div class="q-avatar q-avatar--icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg>
                </div>
                <div>
                    <div class="q-eyebrow">Fahrzeug</div>
                    <h1 class="q-title">{{ $vehicle->registration_identifier }}</h1>
                    @if($vehicle->private)
                        <div class="q-meta">
                            <span class="q-chip">
                                <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
                                privat
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $vehicle)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('vehicles.edit', $vehicle) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="vehicleShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="vehicleShowDropdown">
                        @can('email', $vehicle)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $vehicle)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $vehicle)
                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="post">
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

        <div class="q-card mt-2 mt-md-4">
            <div class="q-card__head">Stammdaten</div>
            <div class="q-card__body">

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Typ</div>
                        <div class="q-inforow__value">
                            @if($vehicle->make_model){{ $vehicle->make_model }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                @if($vehicle->current_kilometres)
                    <div class="q-inforow">
                        <span class="q-inforow__icon">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#database"></use></svg>
                        </span>
                        <div class="q-inforow__main">
                            <div class="q-inforow__label">Kilometerstand</div>
                            <div class="q-inforow__value">{{ $vehicle->current_kilometres_string }}</div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        @if ($vehicle->comment)
            <div class="q-card mt-3">
                <div class="q-card__head">Bemerkungen</div>
                <div class="q-card__body">
                    <div class="markdown">
                        {!! Html::fromMarkdown($vehicle->comment) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
