@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('person.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar q-avatar--{{ $person->avatar_colour }}">{{ $person->initials }}</span>
                <div>
                    <div class="q-eyebrow">Person</div>
                    <h1 class="q-title">{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $person)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('people.edit', $person) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="personShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="personShowDropdown">
                        @can('email', $person)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $person)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $person)
                            <form action="{{ route('people.destroy', ['person' => $person, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-fieldcols mt-4">

            {{-- Left: Allgemeines + Kontakt stacked --}}
            <div class="d-flex flex-column gap-3">

                <div class="q-card">
                    <div class="q-card__head">Allgemeines</div>
                    <div class="q-card__body">

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Firma</div>
                                <div class="q-inforow__value">
                                    @if($person->company)
                                        <a href="{{ route('companies.show', $person->company) }}">{{ $person->company->full_name }}</a>
                                    @else
                                        <span class="q-inforow__value--empty">nicht angegeben</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#grid"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Abteilung</div>
                                <div class="q-inforow__value">
                                    @if($person->department){{ $person->department }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#award"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Rolle</div>
                                <div class="q-inforow__value">
                                    @if($person->role){{ $person->role }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="q-card">
                    <div class="q-card__head">Kontakt</div>
                    <div class="q-card__body">

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#telephone"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Telefon geschäftlich</div>
                                <div class="q-inforow__value">
                                    @if($person->phone_company){{ $person->phone_company }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#phone"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Telefon mobil</div>
                                <div class="q-inforow__value">
                                    @if($person->phone_mobile){{ $person->phone_mobile }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#telephone"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Telefon privat</div>
                                <div class="q-inforow__value">
                                    @if($person->phone_private){{ $person->phone_private }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Fax</div>
                                <div class="q-inforow__value">
                                    @if($person->fax){{ $person->fax }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Email</div>
                                <div class="q-inforow__value text-truncate">
                                    @if($person->email)<a href="mailto:{{ $person->email }}">{{ $person->email }}</a>@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                        <div class="q-inforow">
                            <span class="q-inforow__icon">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#link-45deg"></use></svg>
                            </span>
                            <div class="q-inforow__main">
                                <div class="q-inforow__label">Webseite</div>
                                <div class="q-inforow__value text-truncate">
                                    @if($person->website)<a href="{{ $person->website }}" target="_blank">{{ $person->website }}</a>@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @if ($person->comment)
                    <div class="q-card">
                        <div class="q-card__head">Bemerkungen</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($person->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right: Privatadresse --}}
            <div class="q-card">
                @if ($person->address->first())
                    <div class="q-address__map">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                    </div>
                    <div class="q-address__body">
                        <div class="q-address__label">Privatadresse</div>
                        <div class="q-address__lines">
                            {{ $person->address->first()->street_number }}<br>
                            {{ $person->address->first()->postcode }} {{ $person->address->first()->city }}
                        </div>
                        <a class="btn q-btn w-100 d-inline-flex align-items-center justify-content-center gap-2" href="https://maps.google.com?q={{ $person->address->first()->address_line }}" target="_blank">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                            Google Maps öffnen
                        </a>
                    </div>
                @else
                    <div class="q-address__map">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                    </div>
                    <div class="q-address__body">
                        <div class="q-address__label">Privatadresse</div>
                        <div class="q-placeholder">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                            Keine Adresse
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection
