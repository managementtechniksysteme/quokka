@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('vehicle.breadcrumb')

        <div class="q-page-head">
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

        <div class="q-card mt-4">
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
