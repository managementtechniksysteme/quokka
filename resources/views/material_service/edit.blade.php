@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('material_service.breadcrumb')

            <h3>
                Materialleistung bearbeiten
                <small class="text-muted">{{ $materialService->name }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('material-services.update', $materialService) }}" method="post" novalidate>
            @method('PATCH')
            @component('material_service.fields', [ 'materialService' => $materialService ])
            @endcomponent

            <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                </svg>
                Materialleistung speichern
            </button>
        </form>
    </div>
@endsection
