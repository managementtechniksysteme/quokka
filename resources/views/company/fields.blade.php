@php use \App\Models\Address; @endphp

@if (old('address_id'))
    @php $currentAddress = Address::find(old('address_id')); @endphp
@endif

@if (old('operator_address_id'))
    @php $currentOperatorAddress = Address::find(old('operator_address_id')); @endphp
@endif

@if (old('contact_person_id'))
    @php $currentContactPerson = Person::find(old('contact_person_id')); @endphp
@endif

@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten der Firma.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
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

        <div>
            <label for="name_2">Zusatz (zweiter Name)</label>
            <input type="text" class="form-control @error('name_2') is-invalid @enderror" id="name_2" name="name_2" placeholder="" value="{{ old('name_2', optional($company)->name_2) }}" />
            <div class="invalid-feedback">
                @error('name_2')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            {{-- The picker itself renders once, unchanged, at every
                 breakpoint — only its "add new" trigger button differs
                 (desktop: unchanged text-labelled button toggling the inline
                 collapse below; mobile: icon-only button opening a sheet
                 instead, see further down). --}}
            <div class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label for="address_id">Adresse</label>
                    <address-dropdown :addresses="{{ $addresses }}" :current_address="{{ $currentAddress ?? 'null' }}" v-cloak></address-dropdown>
                    <div class="invalid-feedback @error('address_id') d-block @enderror">
                        @error('address_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <button class="btn q-btn d-none d-md-inline-flex align-items-center gap-2 flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#newAddressFields">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Neue Adresse
                </button>
                {{-- Mobile: the text-labelled button squeezed the dropdown too
                     narrow next to it (2026-07-21, user). Icon-only, same
                     secondary/muted treatment as other mobile icon buttons
                     (e.g. a list's PDF-export button) — opens a sheet with
                     the same fields instead of the inline collapse. Its
                     inputs are NOT the real form fields (that would submit
                     twice under one name) — they mirror the real
                     #address_name/etc. inputs below via JS on open/close, so
                     only one copy of each value is ever actually submitted. --}}
                <button class="btn q-btn q-btn-icon d-md-none flex-shrink-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#newAddressSheet" aria-controls="newAddressSheet" aria-label="Neue Adresse">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                </button>
            </div>

            <div class="q-quick-create-summary mt-2 d-none d-md-none" data-quick-create-summary-for="newAddressSheet">
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                    <span class="q-quick-create-summary__text"></span>
                </span>
                <button type="button" class="q-quick-create-summary__clear" aria-label="Neue Adresse entfernen">
                    <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                </button>
            </div>

            {{-- Desktop: unchanged, byte-for-byte. --}}
            <div class="collapse mt-2 q-new-address-collapse @if (old('address_name') || old('street_number') || old('postcode') || old('city')) show @endif" id="newAddressFields">
                <div class="d-flex flex-column gap-3 p-3 bg-body-secondary rounded">
                    <div>
                        <label for="address_name">Name</label>
                        <input type="text" class="form-control @error('address_name') is-invalid @enderror" id="address_name" name="address_name" placeholder="Musterfirma" value="{{ old('address_name') }}" />
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

            <div class="offcanvas offcanvas-bottom q-sheet d-md-none" tabindex="-1" id="newAddressSheet" aria-label="Neue Adresse"
                 data-quick-create-fields="address_name,street_number,postcode,city"
                 @if (old('address_name') || old('street_number') || old('postcode') || old('city') || $errors->hasAny(['address_name', 'street_number', 'postcode', 'city'])) data-quick-create-open="1" @endif>
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Neue Adresse</div>
                    <div class="d-flex flex-column gap-3 px-3 pb-3">
                        {{-- The real, name-bearing #address_name/etc. inputs (above,
                             desktop-only in the DOM's visible sense) carry the
                             actual submitted value and its @error() — these
                             mirror inputs only duplicate the error MESSAGE for
                             display, not the value itself, which JS syncs in. --}}
                        <div>
                            <label for="address_name_mobile">Name</label>
                            <input type="text" class="form-control @error('address_name') is-invalid @enderror" id="address_name_mobile" data-mirrors="address_name" placeholder="Musterfirma" />
                            <div class="invalid-feedback">
                                @error('address_name')
                                    {{ $message }}
                                @else
                                    Gib bitte den Namen der Adresse ein.
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="street_number_mobile">Straße und Hausnummer</label>
                            <input type="text" class="form-control @error('street_number') is-invalid @enderror" id="street_number_mobile" data-mirrors="street_number" placeholder="Musterstraße 99" />
                            <div class="invalid-feedback">
                                @error('street_number')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="postcode_mobile">Postleitzahl</label>
                            <input type="text" pattern="\d*" maxlength="5" class="form-control @error('postcode') is-invalid @enderror" id="postcode_mobile" data-mirrors="postcode" placeholder="1234" />
                            <div class="invalid-feedback">
                                @error('postcode')
                                    {{ $message }}
                                @else
                                    Gib bitte eine gültige Postleitzahl (bestehend aus Ziffern) ein.
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="city_mobile">Stadt</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city_mobile" data-mirrors="city" placeholder="Musterstadt" />
                            <div class="invalid-feedback">
                                @error('city')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary text-white d-inline-flex align-items-center justify-content-center gap-2" data-bs-dismiss="offcanvas">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            Übernehmen
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            {{-- The picker itself renders once, unchanged, at every
                 breakpoint — only its "add new" trigger button differs,
                 same reasoning as the address_id block above. --}}
            <div class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label for="operator_address_id">Adresse des Betreibers</label>
                    <address-dropdown :inputname="'operator_address_id'" :addresses="{{ $addresses }}" :current_address="{{ $currentOperatorAddress ?? 'null' }}"></address-dropdown>
                    <div class="invalid-feedback @error('operator_address_id') d-block @enderror">
                        @error('operator_address_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <button class="btn q-btn d-none d-md-inline-flex align-items-center gap-2 flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#newOperatorAddressFields">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Neue Adresse
                </button>
                <button class="btn q-btn q-btn-icon d-md-none flex-shrink-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#newOperatorAddressSheet" aria-controls="newOperatorAddressSheet" aria-label="Neue Adresse">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                </button>
            </div>

            <div class="q-quick-create-summary mt-2 d-none d-md-none" data-quick-create-summary-for="newOperatorAddressSheet">
                <span class="q-chip">
                    <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg>
                    <span class="q-quick-create-summary__text"></span>
                </span>
                <button type="button" class="q-quick-create-summary__clear" aria-label="Neue Adresse entfernen">
                    <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                </button>
            </div>

            {{-- Desktop: unchanged, byte-for-byte. --}}
            <div class="collapse mt-2 q-new-address-collapse @if (old('operator_address_name') || old('operator_street_number') || old('operator_postcode') || old('operator_city')) show @endif" id="newOperatorAddressFields">
                <div class="d-flex flex-column gap-3 p-3 bg-body-secondary rounded">
                    <div>
                        <label for="operator_address_name">Name</label>
                        <input type="text" class="form-control @error('operator_address_name') is-invalid @enderror" id="operator_address_name" name="operator_address_name" placeholder="Musterfirma" value="{{ old('operator_address_name') }}" />
                        <div class="invalid-feedback">
                            @error('operator_address_name')
                                {{ $message }}
                            @else
                                Gib bitte den Namen der Adresse ein.
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="operator_street_number">Straße und Hausnummer</label>
                        <input type="text" class="form-control @error('operator_street_number') is-invalid @enderror" id="operator_street_number" name="operator_street_number" placeholder="Musterstraße 99" value="{{ old('operator_street_number') }}" />
                        <div class="invalid-feedback">
                            @error('operator_street_number')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div>
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
                    <div>
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

            <div class="offcanvas offcanvas-bottom q-sheet d-md-none" tabindex="-1" id="newOperatorAddressSheet" aria-label="Neue Adresse"
                 data-quick-create-fields="operator_address_name,operator_street_number,operator_postcode,operator_city"
                 @if (old('operator_address_name') || old('operator_street_number') || old('operator_postcode') || old('operator_city') || $errors->hasAny(['operator_address_name', 'operator_street_number', 'operator_postcode', 'operator_city'])) data-quick-create-open="1" @endif>
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Neue Adresse</div>
                    <div class="d-flex flex-column gap-3 px-3 pb-3">
                        <div>
                            <label for="operator_address_name_mobile">Name</label>
                            <input type="text" class="form-control @error('operator_address_name') is-invalid @enderror" id="operator_address_name_mobile" data-mirrors="operator_address_name" placeholder="Musterfirma" />
                            <div class="invalid-feedback">
                                @error('operator_address_name')
                                    {{ $message }}
                                @else
                                    Gib bitte den Namen der Adresse ein.
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="operator_street_number_mobile">Straße und Hausnummer</label>
                            <input type="text" class="form-control @error('operator_street_number') is-invalid @enderror" id="operator_street_number_mobile" data-mirrors="operator_street_number" placeholder="Musterstraße 99" />
                            <div class="invalid-feedback">
                                @error('operator_street_number')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="operator_postcode_mobile">Postleitzahl</label>
                            <input type="text" pattern="\d*" maxlength="5" class="form-control @error('operator_postcode') is-invalid @enderror" id="operator_postcode_mobile" data-mirrors="operator_postcode" placeholder="1234" />
                            <div class="invalid-feedback">
                                @error('operator_postcode')
                                    {{ $message }}
                                @else
                                    Gib bitte eine gültige Postleitzahl (bestehend aus Ziffern) ein.
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="operator_city_mobile">Stadt</label>
                            <input type="text" class="form-control @error('operator_city') is-invalid @enderror" id="operator_city_mobile" data-mirrors="operator_city" placeholder="Musterstadt" />
                            <div class="invalid-feedback">
                                @error('operator_city')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary text-white d-inline-flex align-items-center justify-content-center gap-2" data-bs-dismiss="offcanvas">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            Übernehmen
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($company)
            <div class="q-banner q-banner--info">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                <span>Die Ansprechperson wird dieser Firma zugeordnet, falls sie aktuell noch keiner anderen Firma zugeordnet ist.</span>
            </div>
        @endif

        <div>
            <label for="contact_person_id">Ansprechperson</label>
            <person-dropdown inputname="contact_person_id" :people="{{ $people }}" :current_person="{{ $currentContactPerson ?? 'null' }}" v-cloak></person-dropdown>
            <div class="invalid-feedback @error('contact_person_id') d-block @enderror">
                @error('contact_person_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Kommunikation
        <div class="q-form-section__desc">Wege, um mit der Firma in Kontakt zu treten.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="phone">Telefon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="+431234567890" value="{{ old('phone', optional($company)->phone) }}" />
            <div class="invalid-feedback">
                @error('phone')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="fax">Fax</label>
            <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" placeholder="+431234567890" value="{{ old('fax', optional($company)->fax) }}" />
            <div class="invalid-feedback">
                @error('fax')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@example.com" value="{{ old('email', optional($company)->email) }}" />
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
            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" placeholder="https://example.com" value="{{ old('website', optional($company)->website) }}" />
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
        <div class="q-form-section__desc">Sonstige Bemerkungen zur Firma.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zur Firma" value="{{ old('comment', optional($company)->comment) }}" :employees="{{ $employees }}" v-cloak></markdown-editor>
        <a class="q-link--quiet d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
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
