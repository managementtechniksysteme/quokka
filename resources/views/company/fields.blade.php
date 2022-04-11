@php use \App\Models\Address; @endphp

@if (old('address_id'))
    @php $currentAddress = Address::find(old('address_id')); @endphp
@endif

@if (old('operator_address_id'))
    @php $currentOperatorAddress = Address::find(old('operator_address_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten der Firma.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musterfirma" value="{{ old('name', optional($company)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen der Firma ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name_2">Zusatz (zweiter Name)</label>
            <input type="text" class="form-control @error('name_2') is-invalid @enderror" id="name_2" name="name_2" placeholder="" value="{{ old('name_2', optional($company)->name_2) }}" />
            <div class="invalid-feedback">
                @error('name_2')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col">
                <label for="address_id">Adresse</label>
                <address-dropdown :addresses="{{ $addresses }}" :current_address="{{ $currentAddress ?? 'null' }}" v-cloak></address-dropdown>
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
                <label for="name">Name</label>
                <input type="text" class="form-control @error('address_name') is-invalid @enderror" id="address_name" name="address_name" placeholder="Musterfirma" value="{{ old('address_name') }}" />
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

        <div class="form-row">
            <div class="form-group col">
                <label for="address_id">Adresse des Betreibers</label>
                <address-dropdown input_name="operator_address_id" :addresses="{{ $addresses }}" :current_address="{{ $currentOperatorAddress ?? 'null' }}"></address-dropdown>
                <div class="invalid-feedback @error('operator_address_id') d-block @enderror">
                    @error('operator_address_id')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group d-none d-lg-block col-lg-auto">
                <label>&nbsp;</label>
                <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-toggle="collapse" data-target="#newOperatorAddressFields">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                    </svg>
                    Neue Adresse anlegen
                </button>
            </div>
        </div>

        <button class="btn btn-outline-secondary d-flex align-items-center d-lg-none mb-3" type="button" data-toggle="collapse" data-target="#newOperatorAddressFields">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
            </svg>
            Neue Adresse anlegen
        </button>

        <div class="collapse @if (old('operator_address_name') || old('operator_street_number') || old('operator_postcode') || old('operator_city')) show @endif" id="newOperatorAddressFields">

            <div class="form-group">
                <label for="operator_name">Name</label>
                <input type="text" class="form-control @error('operator_address_name') is-invalid @enderror" id="operator_address_name" name="operator_address_name" placeholder="Musterfirma" value="{{ old('operator_address_name') }}" />
                <div class="invalid-feedback">
                    @error('operator_address_name')
                        {{ $message }}
                    @else
                        Gib bitte den Namen der Adresse ein.
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="operator_street_number">Straße und Hausnummer</label>
                <input type="text" class="form-control @error('operator_street_number') is-invalid @enderror" id="operator_street_number" name="operator_street_number" placeholder="Musterstraße 99" value="{{ old('operator_street_number') }}" />
                <div class="invalid-feedback">
                    @error('operator_street_number')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="operator_postcode">Postleitzahl</label>
                <input type="text" pattern="\d*" maxlength="5" class="form-control @error('operator_postcode') is-invalid @enderror" id="operator_postcode" name="operator_postcode" placeholder="1234" value="{{ old('operator_postcode') }}" />
                <div class="invalid-feedback">
                    @error('operator_postcode')
                        {{ $message }}
                    @else
                        Gib bitte eine gültige Postleitzahl (bestehend aus Ziffern) ein.
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="operator_city">Stadt</label>
                <input type="text" class="form-control @error('operator_city') is-invalid @enderror" id="operator_city" name="operator_city" placeholder="Musterstadt" value="{{ old('operator_city') }}" />
                <div class="invalid-feedback">
                    @error('operator_city')
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
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#phone"></use>
            </svg>
            Kommunikation
        </p>
        <p class="text-muted">
            Wege, um mit der Firma in Kontakt zu treten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="+431234567890" value="{{ old('phone', optional($company)->phone) }}" />
            <div class="invalid-feedback">
                @error('phone')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="fax">Fax</label>
            <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" placeholder="+431234567890" value="{{ old('fax', optional($company)->fax) }}" />
            <div class="invalid-feedback">
                @error('fax')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($company)->email) }}" />
            <div class="invalid-feedback">
                @error('email')
                    {{ $message }}
                @else
                    Gib bitte eine gültige E-Mail Addresse ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="website">Webseite</label>
            <input type="url" class="form-control @error('website') is-invalid @enderror" id="sebsite" name="website" placeholder="https://example.com" value="{{ old('website', optional($company)->website) }}" />
            <div class="invalid-feedback">
                @error('website')
                    {{ $message }}
                @else
                    Gib bitte eine gültige Webseite ein.
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
            Sonstige Bemerkungen zur Firma.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <vue-easymde :configs="{maxHeight: '300px', spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zur Firma"  value="{{ old('comment', optional($company)->comment) }}" v-cloak></vue-easymde>
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
