@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('exceptions.index') }}" class="q-appbar__btn" aria-label="Zurück zu Fehlerdateien">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title q-mono">{{ $exception['uuid'] }}</span>
    @can('tools-deleteexceptions')
        <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#exceptionShowActionsSheet" aria-controls="exceptionShowActionsSheet" aria-label="Aktionen">
            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
        </button>
    @endcan
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="exceptionShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('tools-deleteexceptions')
                <form action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post">
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
            @include('exception.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Fehlerdatei</div>
                    <h1 class="q-title q-mono">{{ $exception['uuid'] }}</h1>
                </div>
            </div>

            @can('tools-deleteexceptions')
                <form action="{{ route('exceptions.destroy', $exception['uuid']) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                        Entfernen
                    </button>
                </form>
            @endcan
        </div>

        <div class="q-card mt-2 mt-md-0">
            <div class="q-card__body" style="overflow-x: auto;">
                <pre class="mb-0 q-mono q-exception-content" style="font-size: .8rem;">{{ $exception['content'] }}</pre>
            </div>
        </div>
    </div>
@endsection
