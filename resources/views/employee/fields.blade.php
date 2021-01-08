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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Mitarbeiters.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-row">
                <div class="form-group col pr-0">
                    <label for="person_id">Person</label>
                    <person-dropdown :people="{{ $people }}" :current_person="{{ $currentPerson ?? 'null' }}"></person-dropdown>
                    <div class="invalid-feedback @error('person_id') d-block @enderror">
                        @error('person_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group col-auto pl-0 ml-1">
                    <label>&nbsp;</label>
                    <a class="btn btn-outline-secondary d-flex align-items-center" href="{{ route('people.create') }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Person anlegen
                    </a>
            </div>
        </div>

        <div class="form-group">
            <label for="entered_on">Eintrittsdatum</label>
            <input type="date" class="form-control @error('entered_on') is-invalid @enderror" id="entered_on" name="entered_on" placeholder="" value="{{ old('entered_on', optional(optional($employee)->entered_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('entered_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="left_on">Austrittsdatum</label>
            <input type="date" class="form-control @error('left_on') is-invalid @enderror" id="left_on" name="left_on" placeholder="" value="{{ old('left_on', optional(optional($employee)->left_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('left_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="holidays">Urlaubstage</label>
            <input type="number" min="0" class="form-control @error('holidays') is-invalid @enderror" id="holidays" name="holidays" placeholder="25" value="{{ old('holidays', optional($employee)->holidays) }}" required />
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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
            </svg>
            Quokka Benutzer
        </p>
        <p class="text-muted">
            Die Quokka Benutzer Einstellungen des Mitarbeiters.
        </p>

        <p class="text-muted">
            Es muss kein Zugang angelegt werden, wenn der Mitarbeiter nur im System gespeichert werden soll, aber nicht
            aktiv in Quokka arbeitet. Wenn das Passwort beim Bearbeiten leer gelassen wird, bleibt das alte
            Passwort bestehen. Wird ein neues Passwort beim Bearbeiten vergeben, so wird der Benutzer zur Sicherheit in
            allen aktiven Sitzungen abgemeldet.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="username">Benutzername</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="qu" value="{{ old('username', optional(optional($employee)->user)->username) }}" />
            <div class="invalid-feedback">
                @error('username')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="password">Passwort</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" />
            <div class="invalid-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Passwort bestätigen</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" />
            <div class="invalid-feedback">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="avatar_colour">Avatar Farbe</label>
            <avatar-colour-selector :avatar_colours="{{ $avatarColours ?? '[]' }}" :current_avatar_colour="{{ $currentAvatarColour ?? 'null' }}" v-cloak></avatar-colour-selector>
        </div>

    </div>

</div>
