@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                </svg>
                Mitarbeiter
                @if($employees)
                    <small class="text-muted">{{ $employees->total() }} Einträge</small>
                @endif
            </h3>

            <div class="scroll-x d-flex">
                @can('create', \App\Models\Employee::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('employees.create') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Mitarbeiter anlegen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        @unless ($employees->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col">

                    <form action="{{ route('employees.index') }}" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Mitarbeiter suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($employees as $employee)
                @component('employee.overview_card', [ 'employee' => $employee ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Mitarbeiteer passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Mitarbeiter im System vorhanden.</p>
                        @can('create', \App\Models\Employee::class)
                            <p class="lead">Lege einen neuen Mitarbeiter an.</p>
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('employees.create') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Mitarbeiter anlegen
                            </a>
                        @endcan
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $employees->links() }}
        </div>

    </div>
@endsection
