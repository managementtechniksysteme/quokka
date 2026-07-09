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

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten der Person.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-form__row q-form__row--2">
            <div>
                <label for="title_prefix">Namenszusatz Präfix</label>
                <input type="text" class="form-control @error('title_prefix') is-invalid @enderror" id="title_prefix" name="title_prefix" placeholder="Dr." value="{{ old('title_prefix', optional($person)->title_prefix) }}" />
                <div class="invalid-feedback">
                    @error('title_prefix')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <label for="title_suffix">Namenszusatz Suffix</label>
                <input type="text" class="form-control @error('title_suffix') is-invalid @enderror" id="title_suffix" name="title_suffix" placeholder="MSc" value="{{ old('title_suffix', optional($person)->title_suffix) }}" />
                <div class="invalid-feedback">
                    @error('title_suffix')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
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
            <div>
                <label for="last_name">Nachname</label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Mustermann" value="{{ old('last_name', optional($person)->last_name) }}" required />
                <div class="invalid-feedback">
                    @error('last_name')
                        {{ $message }}
                    @else
                        Gib bitte den Nachnamen der Person ein.
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label>Geschlecht</label>
            <div class="btn-group @error('gender') is-invalid @enderror">
                <input type="radio" class="btn-check" name="gender" id="gender-male" value="male" autocomplete="off" @if(old('gender', optional($person)->gender) == 'male') checked @endif>
                <label class="btn btn-outline-secondary" for="gender-male">männlich</label>
                <input type="radio" class="btn-check" name="gender" id="gender-female" value="female" autocomplete="off" @if(old('gender', optional($person)->gender) == 'female') checked @endif>
                <label class="btn btn-outline-secondary" for="gender-female">weiblich</label>
                <input type="radio" class="btn-check" name="gender" id="gender-neutral" value="neutral" autocomplete="off" @if(old('gender', optional($person)->gender) == 'neutral') checked @endif>
                <label class="btn btn-outline-secondary" for="gender-neutral">neutral</label>
            </div>
            <div class="invalid-feedback @error('gender') d-block @enderror">
                @error('gender')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <div class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label for="address_id">Privatadresse</label>
                    <address-dropdown :addresses="{{ $addresses }}" :current_address="{{ $currentAddress ?? 'null' }}" v-cloak></address-dropdown>
                    <div class="invalid-feedback @error('address_id') d-block @enderror">
                        @error('address_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <button class="btn q-btn d-flex align-items-center gap-2 flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#newAddressFields">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Neue Adresse
                </button>
            </div>

            <div class="collapse mt-2 @if (old('address_name') || old('street_number') || old('postcode') || old('city')) show @endif" id="newAddressFields">
                <div class="d-flex flex-column gap-3 p-3 bg-body-secondary rounded">
                    <div>
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
                    <div>
                        <label for="street_number">Straße und Hausnummer</label>
                        <input type="text" class="form-control @error('street_number') is-invalid @enderror" id="street_number" name="street_number" placeholder="Musterstraße 99" value="{{ old('street_number') }}" />
                        <div class="invalid-feedback">
                            @error('street_number')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div>
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
                    <div>
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
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Firmenzugehörigkeit
        <div class="q-form-section__desc">Details zur Anstellung der Person.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        @if($currentCompany && $person && $currentCompany->contact_person_id === $person->id)
            <div class="q-banner q-banner--info">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                <span>Die Person ist als Ansprechperson für diese Firma eingetragen. Bei Änderung oder Entfernung der Firmenzugehörigkeit wird die Ansprechperson der Firma entfernt.</span>
            </div>
        @endif

        <div>
            <label for="company_id">Firma</label>
            <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}"></company-dropdown>
            <div class="invalid-feedback @error('company_id') d-block @enderror">
                @error('company_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="department">Abteilung</label>
            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" placeholder="Entwicklung" value="{{ old('department', optional($person)->department) }}" />
            <div class="invalid-feedback">
                @error('department')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="role">Rolle</label>
            <input type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role" placeholder="Techniker" value="{{ old('role', optional($person)->role) }}" />
            <div class="invalid-feedback">
                @error('role')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Kommunikation
        <div class="q-form-section__desc">Wege, um mit der Person in Kontakt zu treten.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="phone_company">Telefon geschäftlich</label>
            <input type="text" class="form-control @error('phone_company') is-invalid @enderror" id="phone_company" name="phone_company" placeholder="+431234567890" value="{{ old('phone_company', optional($person)->phone_company) }}" />
            <div class="invalid-feedback">
                @error('phone_company')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="phone_mobile">Telefon mobil</label>
            <input type="text" class="form-control @error('phone_mobile') is-invalid @enderror" id="phone_mobile" name="phone_mobile" placeholder="+431234567890" value="{{ old('phone_mobile', optional($person)->phone_mobile) }}" />
            <div class="invalid-feedback">
                @error('phone_mobile')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="phone_private">Telefon privat</label>
            <input type="text" class="form-control @error('phone_private') is-invalid @enderror" id="phone_private" name="phone_private" placeholder="+431234567890" value="{{ old('phone_private', optional($person)->phone_private) }}" />
            <div class="invalid-feedback">
                @error('phone_private')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="fax">Fax</label>
            <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" placeholder="+431234567890" value="{{ old('fax', optional($person)->fax) }}" />
            <div class="invalid-feedback">
                @error('fax')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($person)->email) }}" />
            <div class="invalid-feedback">
                @error('email')
                    {{ $message }}
                @else
                    Gib bitte eine gültige E-Mail Adresse ein.
                @enderror
            </div>
        </div>

        <div>
            <label for="website">Webseite</label>
            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" placeholder="https://example.com" value="{{ old('website', optional($person)->website) }}" />
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zur Person.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zur Person" value="{{ old('comment', optional($person)->comment) }}" v-cloak></markdown-editor>
        <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
            <svg class="icon-bs icon-16 me-1"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg>
            Hilfe zu Markdown
        </a>
        <div class="invalid-feedback @error('comment') d-block @enderror">
            @error('comment')
                {{ $message }}
            @enderror
        </div>
    </div>
</div>
