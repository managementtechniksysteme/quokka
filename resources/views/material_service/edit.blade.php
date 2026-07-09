@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('material_service.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Materialleistung bearbeiten</div>
                    <h1 class="q-title">{{ $materialService->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('material-services.update', $materialService) }}" method="post" novalidate>
            @method('PATCH')
            @include('material_service.fields', ['materialService' => $materialService])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('material-services.show', $materialService) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#save"></use></svg>
                    Materialleistung speichern
                </button>
            </div>
        </form>
    </div>
@endsection
