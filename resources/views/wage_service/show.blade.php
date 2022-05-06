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
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $wageService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('wage-services.edit', $wageService) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $wageService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $wageService)
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
                @can('delete', $wageService)
                    <form action="{{ route('wage-services.destroy', $wageService) }}" method="post" >
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
        @component('service.show', [ 'service' => $wageService ])
        @endcomponent

        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#circle"></use>
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
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
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
