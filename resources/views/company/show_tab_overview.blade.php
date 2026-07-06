@extends('company.show')

@section('tab')
    <div class="q-fieldcols">

        {{-- Kontakt --}}
        <div class="q-card">
            <div class="q-card__head">Kontakt</div>
            <div class="q-card__body">

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Telefon</div>
                        <div class="q-inforow__value">
                            @if ($company->phone){{ $company->phone }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Fax</div>
                        <div class="q-inforow__value">
                            @if ($company->fax){{ $company->fax }}@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Email</div>
                        <div class="q-inforow__value text-truncate">
                            @if ($company->email)<a href="mailto:{{ $company->email }}">{{ $company->email }}</a>@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    <span class="q-inforow__icon">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#link-2"></use></svg>
                    </span>
                    <div class="q-inforow__main">
                        <div class="q-inforow__label">Webseite</div>
                        <div class="q-inforow__value text-truncate">
                            @if ($company->website)<a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>@else<span class="q-inforow__value--empty">nicht angegeben</span>@endif
                        </div>
                    </div>
                </div>

                <div class="q-inforow">
                    @if ($company->contactPerson)
                        <span class="q-avatar q-avatar--round q-avatar--sm q-avatar--{{ $company->contactPerson->avatar_colour }}">{{ $company->contactPerson->initials }}</span>
                        <div class="q-inforow__main">
                            <div class="q-inforow__label">Ansprechperson</div>
                            <div class="q-inforow__value text-truncate">
                                <a href="{{ route('people.show', $company->contactPerson) }}">{{ $company->contactPerson->name }}</a>
                            </div>
                        </div>
                    @else
                        <span class="q-inforow__icon">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use></svg>
                        </span>
                        <div class="q-inforow__main">
                            <div class="q-inforow__label">Ansprechperson</div>
                            <div class="q-inforow__value"><span class="q-inforow__value--empty">nicht angegeben</span></div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Adresse --}}
        <div class="q-card">
            @if ($company->address->first())
                <div class="q-address__map">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use></svg>
                </div>
                <div class="q-address__body">
                    <div class="q-address__label">Adresse</div>
                    <div class="q-address__lines">
                        {{ $company->address->first()->street_number }}<br />
                        {{ $company->address->first()->postcode }} {{ $company->address->first()->city }}
                    </div>
                    <a class="btn q-btn w-100 d-inline-flex align-items-center justify-content-center gap-2" href="https://maps.google.com?q={{ $company->address->first()->address_line }}" target="_blank">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use></svg>
                        Google Maps öffnen
                    </a>
                </div>
            @else
                <div class="q-address__body">
                    <div class="q-address__label">Adresse</div>
                    <div class="q-inforow__value--empty">keine Adresse angegeben</div>
                </div>
            @endif
        </div>

    </div>

    {{-- Bemerkungen --}}
    @if ($company->comment)
        <div class="q-card mt-3">
            <div class="q-card__head">Bemerkungen</div>
            <div class="q-card__body">
                <div class="markdown">
                    {!! Html::fromMarkdown($company->comment) !!}
                </div>
            </div>
        </div>
    @endif
@endsection
