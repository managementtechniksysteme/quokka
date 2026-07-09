@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Mitarbeiter</h1>
                    @unless($employees->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $employees->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\Employee::class)
                <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('employees.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Mitarbeiter anlegen
                </a>
            @endcan
        </div>

        @unless ($employees->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('employees.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Mitarbeiter suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" href="{{ Request::url() }}">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endunless

        @if($employees->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Mitarbeiter für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Mitarbeiter vorhanden.</p>
                    @can('create', \App\Models\Employee::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('employees.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Mitarbeiter anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($employees as $employee)
                    @include('employee.overview_card_content', ['employee' => $employee])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
@endsection
