@extends('layouts.app')

@section('content')
    <div class="q-container">
        @include('exception.breadcrumb')

        <div class="q-page-head">
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

        <div class="q-card">
            <div class="q-card__body" style="overflow-x: auto;">
                <pre class="mb-0 q-mono" style="font-size: .8rem;">{{ $exception['content'] }}</pre>
            </div>
        </div>
    </div>
@endsection
