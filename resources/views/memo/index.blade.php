@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Aktenvermerke</h1>
                    @unless($memos->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $memos->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\Memo::class)
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('memos.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Aktenvermerk anlegen
                </a>
            @endcan

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($memos->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $memos->total()) }}</div>
                @endunless
                @can('create', \App\Models\Memo::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('memos.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Aktenvermerk
                    </a>
                @endcan
            </div>
        </div>

        @unless ($memos->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + quick-filter split-dropdown + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('memos.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aktenvermerke suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                        <button type="button" class="btn q-btn dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=von:' . Auth::user()->username . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=von:' . Auth::user()->username }}" @endif>
                                Meine Aktenvermerke
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=von:' . Auth::user()->username . ' ist:entwurf&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=von:' . Auth::user()->username . ' ist:entwurf' }}" @endif>
                                Meine Entwürfe
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=bm:' . Auth::user()->username . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=bm:' . Auth::user()->username }}" @endif>
                                Beteiligte Aktenvermerke
                            </a>
                        </div>
                    </div>
                </form>

                <div class="dropdown ms-auto">
                    <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('memos.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="number-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Nummer
                            </button>
                            <button type="submit" name="sort" value="number-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Nummer
                            </button>
                            <button type="submit" name="sort" value="meeting_held_on-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="meeting_held_on-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="title-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Titel
                            </button>
                            <button type="submit" name="sort" value="title-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Titel
                            </button>
                            <button type="submit" name="sort" value="draft-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Entwurf
                            </button>
                            <button type="submit" name="sort" value="draft-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Entwurf
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button. Filter joins the input-group as a fused
                 trailing segment (matches desktop's split-dropdown being
                 attached to the field); sort stays a standalone icon button
                 outside the group. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('memos.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aktenvermerke suchen" autocomplete="off" />
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                            <button type="button" class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#memoQuickFilterSheet" aria-controls="memoQuickFilterSheet" aria-label="Schnellfilter">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                            </button>
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#memoSortSheet" aria-controls="memoSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            @php
                $memoFilterUsername = Auth::user()->username;
                $memoQuickFilters = [
                    'Meine Aktenvermerke' => 'von:' . $memoFilterUsername,
                    'Meine Entwürfe' => 'von:' . $memoFilterUsername . ' ist:entwurf',
                    'Beteiligte Aktenvermerke' => 'bm:' . $memoFilterUsername,
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="memoQuickFilterSheet" aria-label="Schnellfilter">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Schnellfilter</div>
                    @foreach($memoQuickFilters as $quickFilterLabel => $quickFilterExpr)
                        <a class="q-row" href="{{ Request::url() . '?search=' . urlencode($quickFilterExpr) . (Request::get('sort') ? '&sort=' . Request::get('sort') : '') }}">
                            <span class="q-row__title">{{ $quickFilterLabel }}</span>
                            @if(Request::get('search') === $quickFilterExpr)
                                <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            @php
                $memoSortOptions = [
                    'number-asc' => ['Nummer', 'arrow-up'],
                    'number-desc' => ['Nummer', 'arrow-down'],
                    'meeting_held_on-asc' => ['Datum', 'arrow-up'],
                    'meeting_held_on-desc' => ['Datum', 'arrow-down'],
                    'title-asc' => ['Titel', 'arrow-up'],
                    'title-desc' => ['Titel', 'arrow-down'],
                    'draft-asc' => ['Entwurf', 'arrow-up'],
                    'draft-desc' => ['Entwurf', 'arrow-down'],
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="memoSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('memos.index') }}" method="get">
                        @if(request()->search)
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif
                        @foreach($memoSortOptions as $sortValue => $sortMeta)
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

        @if($memos->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Aktenvermerke für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Aktenvermerke vorhanden.</p>
                    @can('create', \App\Models\Memo::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('memos.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Aktenvermerk anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($memos as $memo)
                    @include('memo.overview_card_content', ['memo' => $memo, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $memos->links() }}
            </div>
        @endif
    </div>
@endsection
