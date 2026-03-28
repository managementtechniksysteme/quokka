@php
    use \App\Models\Person;
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('involved_ids'))
    @php $currentInvolvedEmployees = Person::order()->find(old('involved_ids'))->toJson(); @endphp
@endif

@if (old('present_ids'))
    @php $currentPresentPeople = Person::order()->find(old('present_ids'))->toJson(); @endphp
@endif

@csrf

@unless(Auth::user()->signature())
    <div class="alert alert-warning mt-1" role="alert">
        <div class="d-inline-flex align-items-center">
            <svg class="icon icon-24 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
            </svg>
            <p class="m-0">
                Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch
                eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den
                <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a>
                hinzu.
            </p>
        </div>
    </div>
@endunless

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon-bs icon-16 me-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Bautagesberichtes.
        </p>
        <p class="text-muted">
            Auf PDF Ausdrucken wird die Unterschrift dessen Mitarbeiters eingefügt, welcher den Bautagesbericht erstellt.
            Bei der Bearbeitung eines bereits unterschriebenen Bautagesberichtes wird die vorhandene Unterschrift entferent.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="employee">Ersteller</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($constructionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="mb-3">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($constructionReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 me-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Bautagesbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="minimum_temperature">Minimale Temperatur</label>
            <div class="input-group">
                <input type="number" step="1" class="form-control @error('minimum_temperature') is-invalid @enderror" id="minimum_temperature" name="minimum_temperature" placeholder="18" value="{{ old('minimum_temperature', optional($constructionReport)->minimum_temperature) }}" required />
                    <span class="input-group-text">°C</span>
                <div class="invalid-feedback">
                    @error('minimum_temperature')
                    {{ $message }}
                    @else
                        Gib bitte die minimale Temperatur an.
                        @enderror
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="maximum_temperature">Maximale Temperatur</label>
            <div class="input-group">
                <input type="number" step="1" class="form-control @error('maximum_temperature') is-invalid @enderror" id="maximum_temperature" name="maximum_temperature" placeholder="22" value="{{ old('maximum_temperature', optional($constructionReport)->maximum_temperature) }}" required />
                    <span class="input-group-text">°C</span>
                <div class="invalid-feedback">
                    @error('maximum_temperature')
                    {{ $message }}
                    @else
                        Gib bitte die maximale Temperatur an.
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Leistungsfortschritt
        </p>
        <p class="text-muted">
            Leistungsfortschritt der Arbeiten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label for="comment">
                Leistungsfortschritt
            </label>
            <markdown-editor name="comment" placeholder="Leistungsfortschritt"  value="{{ old('comment', optional($constructionReport)->comment) }}" v-cloak></markdown-editor>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 me-1">
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

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Anhänge
        </p>
        <p class="text-muted">
            Dem Bautagesbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.
        </p>
        <p class="text-muted">
            Der Dateiname von neu hinzugefügten Anhängen kann geändert werden, indem der Text markiert und ein neuer Name eingegeben wird.
        </p>
    </div>

    <div class="col-md-8">
        <div class="mb-3">
            <label>
                Anhänge
            </label>
            <attachments-selector accept="image/*, application/pdf" :current_attachments="{{ $currentAttachments ?? '[]' }}" v-cloak></attachments-selector>
            <div class="invalid-feedback @error('remove_attachments') d-block @enderror @error('remove_attachments.*') d-block @enderror @error('new_attachments') d-block @enderror @error('new_attachments.*') d-block @enderror">
                @error('remove_attachments')
                {{ $message }}
                @enderror
                @error('remove_attachments.*')
                {{ $message }}
                @enderror
                @error('new_attachments')
                {{ $message }}
                @enderror
                @error('new_attachments.*')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 me-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
            </svg>
            Anfrage zur Unterschrift senden
        </p>
        <p class="text-muted">
            Bei Aktivierung der Schaltfläche kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.
        </p>
    </div>

    <div class="col-md-8">
        <div class="alert alert-info" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 me-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
                <label class="form-check-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
