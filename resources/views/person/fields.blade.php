@php
    use \App\Models\Address;
    use \App\Models\Company;
@endphp

@if (old('address_id'))
    @php $currentAddress = Address::find(old('address_id')); @endphp
@endif

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten der Person.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="title_prefix">Namenszusatz Präfix</label>
            <input type="text" class="form-control @error('title_prefix') is-invalid @enderror" id="title_prefix" name="title_prefix" placeholder="Dr." value="{{ old('title_prefix', optional($person)->title_prefix) }}" />
            <div class="invalid-feedback">
                @error('title_prefix')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="first_name">Vorname</label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Max" value="{{ old('first_name', optional($person)->first_name) }}" required />
            <div class="invalid-feedback">
                @error('first_name')
                    {{ $message }}
                @else
                    Gib bitte den Vornamen der Person ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="last_name">Nachname</label>
            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Mustermann" value="{{ old('last_name', optional($person)->last_name) }}" required />
            <div class="invalid-feedback">
                @error('first_name')
                    {{ $message }}
                @else
                    Gib bitte den Nachnamen der Person ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="title_suffix">Namenszusatz Suffix</label>
            <input type="text" class="form-control @error('title_suffix') is-invalid @enderror" id="title_suffix" name="title_suffix" placeholder="MSc" value="{{ old('title_suffix', optional($person)->title_suffix) }}" />
            <div class="invalid-feedback">
                @error('title_suffix')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="gender">Geschlecht</label>
            </div>
            <div class="btn-group btn-group-toggle @error('gender') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('gender', optional($person)->gender) == 'male') active @endif">
                    <input type="radio" name="gender" id="male" value="male" autocomplete="off" @if(old('gender', optional($person)->gender) == 'male') checked @endif> männlich
                </label>
                <label class="btn btn-outline-secondary @if(old('gender', optional($person)->gender) == 'female') active @endif">
                    <input type="radio" name="gender" id="female" value="female" autocomplete="off" @if(old('gender', optional($person)->gender) == 'female') checked @endif> weiblich
                </label>
                <label class="btn btn-outline-secondary @if(old('gender', optional($person)->gender) == 'neutral') active @endif">
                    <input type="radio" name="gender" id="neutral" value="neutral" autocomplete="off" @if(old('gender', optional($person)->gender) == 'neutral') checked @endif> neutral
                </label>
            </div>
            <div class="invalid-feedback @error('gender') d-block @enderror">
                @error('gender')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col">
                <label for="address_id">Privatadresse</label>
                <address-dropdown :addresses="{{ $addresses }}" :current_address="{{ $currentAddress ?? 'null' }}"></address-dropdown>
                <div class="invalid-feedback @error('address_id') d-block @enderror">
                    @error('address_id')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group d-none d-lg-block col-lg-auto">
                <label>&nbsp;</label>
                <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-toggle="collapse" data-target="#newAddressFields">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                    </svg>
                    Neue Adresse anlegen
                </button>
            </div>
        </div>

        <button class="btn btn-outline-secondary d-flex align-items-center d-lg-none mb-3" type="button" data-toggle="collapse" data-target="#newAddressFields">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
            </svg>
            Neue Adresse anlegen
        </button>

        <div class="collapse @if (old('address_name') || old('street_number') || old('postcode') || old('city')) show @endif" id="newAddressFields">

            <div class="form-group">
                <label for="address_name">Name</label>
                <input type="text" class="form-control @error('address_name') is-invalid @enderror" id="address_name" name="address_name" placeholder="Max Mustermann" value="{{ old('address_name') }}" />
                <div class="invalid-feedback">
                    @error('address_name')
                    {{ $message }}
                    @else
                        Gib bitte den Namen der Adresse ein.
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="street_number">Straße und Hausnummer</label>
                <input type="text" class="form-control @error('street_number') is-invalid @enderror" id="street_number" name="street_number" placeholder="Musterstraße 99" value="{{ old('street_number') }}" />
                <div class="invalid-feedback">
                    @error('street_number')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="postcode">Postleitzahl</label>
                <input type="text" pattern="\d*" maxlength="5" class="form-control @error('postcode') is-invalid @enderror" id="postcode" name="postcode" placeholder="1234" value="{{ old('postcode') }}" />
                <div class="invalid-feedback">
                    @error('postcode')
                        {{ $message }}
                    @else
                        Gib bitte eine gültige Postleitzahl (bestehend aus Ziffern) ein.
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="city">Stadt</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="Musterstadt" value="{{ old('city') }}" />
                <div class="invalid-feedback">
                    @error('city')
                        {{ $message }}
                    @enderror
                </div>
            </div>

        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
            </svg>
            Firmenzugehörigkeit
        </p>
        <p class="text-muted">
            Details zur Anstellung der Person.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="company_id">Firma</label>
            <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}"></company-dropdown>
            <div class="invalid-feedback @error('company_id') d-block @enderror">
                @error('company_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name">Abteilung</label>
            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" placeholder="Entwicklung" value="{{ old('department', optional($person)->department) }}" />
            <div class="invalid-feedback">
                @error('department')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name">Rolle</label>
            <input type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role" placeholder="Techniker" value="{{ old('role', optional($person)->role) }}" />
            <div class="invalid-feedback">
                @error('role')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>

</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
            </svg>
            Kommunikation
        </p>
        <p class="text-muted">
            Wege, um mit der Person in Kontakt zu treten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="phone">Telefon geschäftlich</label>
            <input type="text" class="form-control @error('phone_company') is-invalid @enderror" id="phone_company" name="phone_company" placeholder="+431234567890" value="{{ old('phone_company', optional($person)->phone_company) }}" />
            <div class="invalid-feedback">
                @error('phone_company')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Telefon mobil</label>
            <input type="text" class="form-control @error('phone_mobile') is-invalid @enderror" id="phone_mobile" name="phone_mobile" placeholder="+431234567890" value="{{ old('phone_mobile', optional($person)->phone_mobile) }}" />
            <div class="invalid-feedback">
                @error('phone_mobile')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Telefon privat</label>
            <input type="text" class="form-control @error('phone_private') is-invalid @enderror" id="phone_private" name="phone_private" placeholder="+431234567890" value="{{ old('phone_private', optional($person)->phone_private) }}" />
            <div class="invalid-feedback">
                @error('phone_private')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="fax">Fax</label>
            <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" placeholder="+431234567890" value="{{ old('fax', optional($person)->fax) }}" />
            <div class="invalid-feedback">
                @error('fax')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($person)->email) }}" />
            <div class="invalid-feedback">
                @error('email')
                    {{ $message }}
                @else
                    Gib bitte eine gueltige E-Mail Addresse ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="website">Webseite</label>
            <input type="url" class="form-control @error('website') is-invalid @enderror" id="sebsite" name="website" placeholder="https://example.com" value="{{ old('website', optional($person)->website) }}" />
            <div class="invalid-feedback">
                @error('website')
                    {{ $message }}
                @else
                    Gib bitte eine gueltige Webseite ein.
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zur Person.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zur Firma"  value="{{ old('comment', optional($person)->comment) }}"></vue-easymde>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe zu Markdown
            </a>
            <div class="invalid-feedback @error('comment') d-block @enderror">
                @error('comment')
                    {{ $message }}
                @enderror
            </div>
        </div>

    </div>
</div>
