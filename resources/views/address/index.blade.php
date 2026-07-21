@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
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
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('addresses.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Adresse anlegen
                </a>
            @endcan

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($addresses->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $addresses->total()) }}</div>
                @endunless
                @can('create', \App\Models\Address::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('addresses.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Adresse
                    </a>
                @endcan
            </div>
        </div>

        @unless ($addresses->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
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

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button, sort as an icon-only button opening a bottom
                 sheet — matching company/task/person's established pattern. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('addresses.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Adressen suchen" autocomplete="off" />
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#addressSortSheet" aria-controls="addressSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="addressSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('addresses.index') }}" method="get">
                        @if(request()->search)
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif
                        @php
                            $addressSortOptions = [
                                'street-number-asc' => ['Straße Nr', 'arrow-up'],
                                'street-number-desc' => ['Straße Nr', 'arrow-down'],
                                'postcode-asc' => ['Postleitzahl', 'arrow-up'],
                                'postcode-desc' => ['Postleitzahl', 'arrow-down'],
                            ];
                        @endphp
                        @foreach($addressSortOptions as $sortValue => $sortMeta)
                            <button type="submit" name="sort" value="{{ $sortValue }}" class="q-row">
                                <span class="q-avatar q-avatar--muted">
                                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $sortMeta[1] }}"></use></svg>
                                </span>
                                <span class="q-row__title">{{ $sortMeta[0] }}</span>
                                @if(request('sort') === $sortValue)
                                    <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                                @endif
                            </button>
                        @endforeach
                    </form>
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
