@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Adressen</h1>
                    @unless($addresses->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $addresses->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\Address::class)
                <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('addresses.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Adresse anlegen
                </a>
            @endcan
        </div>

        @unless ($addresses->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('addresses.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Adressen suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="dropdown ms-auto">
                    <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('addresses.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="street-number-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Straße Nr
                            </button>
                            <button type="submit" name="sort" value="street-number-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Straße Nr
                            </button>
                            <button type="submit" name="sort" value="postcode-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Postleitzahl
                            </button>
                            <button type="submit" name="sort" value="postcode-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Postleitzahl
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endunless

        @if($addresses->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Adressen für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Adressen vorhanden.</p>
                    @can('create', \App\Models\Address::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('addresses.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Adresse anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($addresses as $address)
                    @include('address.overview_card_content', ['address' => $address])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $addresses->links() }}
            </div>
        @endif
    </div>
@endsection
