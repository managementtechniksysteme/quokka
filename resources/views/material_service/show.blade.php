@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('material_service.breadcrumb')

            <h3>
                Materialleistung
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $materialService->name }}
                    @if(false)
                        <svg class="icon-bs icon-16 text-yellow ms-1">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $materialService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('material-services.edit', $materialService) }}">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $materialService)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon-bs icon-16 me-2">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $materialService)
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
                @can('delete', $materialService)
                    <form action="{{ route('material-services.destroy', $materialService) }}" method="post" >
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
        @component('service.show', [ 'service' => $materialService ])
        @endcomponent
    </div>
@endsection
