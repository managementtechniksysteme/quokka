@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                Rollen
                @if(count($roles))
                    <small class="text-muted">{{ count($roles) }} Einträge</small>
                @endif
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('roles.create') }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                    </svg>
                    Rolle anlegen
                </a>
            </div>
        </div>
    </div>

    <div class="container my-4">
        @unless ($roles->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col">

                    <form action="{{ route('roles.index') }}" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Rollen suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="feather feather-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                        <svg class="feather feather-16">
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
            @forelse ($roles as $role)
                @component('role.overview_card', [ 'role' => $role ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Rollen passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Rollen im System vorhanden.</p>
                        <p class="lead">Lege eine neue Rolle an.</p>
                        <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('roles.create') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Rolle anlegen
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $roles->links() }}
        </div>

    </div>
@endsection