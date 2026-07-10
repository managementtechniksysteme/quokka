@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('wage_service.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Lohndienstleistung</div>
                    <h1 class="q-title">{{ $wageService->name }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $wageService)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('wage-services.edit', $wageService) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="wageServiceShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="wageServiceShowDropdown">
                        @can('email', $wageService)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $wageService)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $wageService)
                            <form action="{{ route('wage-services.destroy', $wageService) }}" method="post">
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

        <div class="q-card">
            <div class="q-card__body">
                @component('service.show', [ 'service' => $wageService ])
                @endcomponent

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#circle"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Einheit</div>
                        <div class="q-inforow__value">{{ $wageService->unit }}</div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Kosten</div>
                        <div class="q-inforow__value{{ $wageService->costs ? '' : ' q-inforow__value--empty' }}">{{ $wageService->costs ? $currencyUnit . ' ' . Number::toLocal($wageService->costs) : 'nicht angegeben' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
