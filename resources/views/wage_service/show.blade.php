@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('wage_service.breadcrumb')

            <h3>
                Lohndienstleistung
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $wageService->name }}
                    @if(false)
                        <svg class="icon-bs icon-16 text-yellow ms-1">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $wageService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('wage-services.edit', $wageService) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $wageService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $wageService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon-bs icon-16 me-2">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $wageService)
                    <form action="{{ route('wage-services.destroy', $wageService) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon-bs icon-16 me-2">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        @component('service.show', [ 'service' => $wageService ])
        @endcomponent

        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 me-2">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#circle"></use>
                    </svg>
                    Einheit
                </div>
            </div>
            <div class="col">
                {{ $wageService->unit }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon-bs icon-16 me-2">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use>
                    </svg>
                    Kosten
                </div>
            </div>
            <div class="col">
                {{ $wageService->costs ? $currencyUnit . ' ' . Number::toLocal($wageService->costs) : 'nicht angegeben' }}
            </div>
        </div>
    </div>
@endsection
