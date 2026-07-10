@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('material_service.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Materialleistung</div>
                    <h1 class="q-title">{{ $materialService->name }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $materialService)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('material-services.edit', $materialService) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="materialServiceShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="materialServiceShowDropdown">
                        @can('email', $materialService)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $materialService)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $materialService)
                            <form action="{{ route('material-services.destroy', $materialService) }}" method="post">
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
                @component('service.show', [ 'service' => $materialService ])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
