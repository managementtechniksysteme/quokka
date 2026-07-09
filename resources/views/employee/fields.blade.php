@php
    use \App\Models\Person;
    use \App\Models\UserSettings;
@endphp

@if (old('person_id'))
    @php $currentPerson = Person::find(old('person_id')); @endphp
@endif

@if(old('avatar_colour'))
    @php $currentAvatarColour = json_encode(UserSettings::avatarColourFromHex(old('avatar_colour'))); @endphp
@endif

@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Mitarbeiters.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="person_id">Person</label>
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <person-dropdown :people="{{ $people }}" :current_person="{{ $currentPerson ?? 'null' }}"></person-dropdown>
                    <div class="invalid-feedback @error('person_id') d-block @enderror">
                        @error('person_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <a class="btn q-btn d-flex align-items-center gap-2 flex-shrink-0" href="{{ route('people.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Person anlegen
                </a>
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="entered_on">Eintrittsdatum</label>
                <input type="date" class="form-control @error('entered_on') is-invalid @enderror" id="entered_on" name="entered_on" value="{{ old('entered_on', optional(optional($employee)->entered_on)->format('Y-m-d')) }}" required />
                <div class="invalid-feedback">
                    @error('entered_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <label for="left_on">Austrittsdatum</label>
                <input type="date" class="form-control @error('left_on') is-invalid @enderror" id="left_on" name="left_on" value="{{ old('left_on', optional(optional($employee)->left_on)->format('Y-m-d')) }}" />
                <div class="invalid-feedback">
                    @error('left_on')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <label for="holidays">Urlaubstage</label>
            <input type="number" step="{{ $holidaysSteps }}" class="form-control @error('holidays') is-invalid @enderror" id="holidays" name="holidays" placeholder="25" value="{{ old('holidays', optional($employee)->holidays) }}" required />
            <div class="invalid-feedback">
                @error('holidays')
                    {{ $message }}
                @else
                    Gib bitte die Anzahl der Urlaubstage (mindestens 0) ein.
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Quokka Benutzer
        <div class="q-form-section__desc">Die Quokka Benutzer Einstellungen des Mitarbeiters. Es muss kein Zugang angelegt werden, wenn der Mitarbeiter nur im System gespeichert werden soll. Wenn das Passwort beim Bearbeiten leer gelassen wird, bleibt das alte Passwort bestehen.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="username">Benutzername</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="qu" value="{{ old('username', optional(optional($employee)->user)->username) }}" />
            <div class="invalid-feedback">
                @error('username')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="password">Passwort</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" />
            <div class="invalid-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="password_confirmation">Passwort bestätigen</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" />
            <div class="invalid-feedback">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="avatar_colour">Avatar Farbe</label>
            <avatar-colour-selector :avatar_colours="{{ $avatarColours ?? '[]' }}" :current_avatar_colour="{{ $currentAvatarColour ?? 'null' }}" v-cloak></avatar-colour-selector>
        </div>
    </div>
</div>
