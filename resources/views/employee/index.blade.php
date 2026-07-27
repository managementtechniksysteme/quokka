@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
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
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('employees.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Mitarbeiter anlegen
                </a>
            @endcan

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($employees->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $employees->total()) }}</div>
                @endunless
                @can('create', \App\Models\Employee::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('employees.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Mitarbeiter
                    </a>
                @endcan
            </div>
        </div>

        @unless ($employees->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('employees.index') }}" method="get">
                    <div class="input-group">
                        <filter-search-input name="search" input_class="form-control" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="employee" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Mitarbeiter suchen"></filter-search-input>
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

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button. No sort here at all — desktop has none either. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('employees.index') }}" method="get">
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <filter-search-input name="search" input_class="form-control ps-5" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="employee" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Mitarbeiter suchen"></filter-search-input>
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
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
