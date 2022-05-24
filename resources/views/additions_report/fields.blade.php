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
            <svg class="icon icon-24 mr-2">
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
            <svg class="icon-bs icon-16 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Regieberichtes.
        </p>
        <p class="text-muted">
            Auf PDF Ausdrucken wird die Unterschrift dessen Mitarbeiters eingefügt, welcher den Regiebericht erstellt.
            Bei der Bearbeitung eines bereits unterschriebenen Regieberichtes wird die vorhandene Unterschrift entferent.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee">Ersteller</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($additionsReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div class="form-group">
            <div>
                <label for="status">Status</label>
            </div>
            @if(optional($additionsReport)->status === 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="icon icon-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Regiebericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-secondary @if(optional($additionsReport)->status == 'new' || !$additionsReport) active @else disabled @endif">
                    <input type="radio" name="status" id="new" @if(optional($additionsReport)->status == 'new' || !$additionsReport) checked @endif disabled> neu
                </label>
                <label class="btn btn-outline-secondary @if(optional($additionsReport)->status == 'signed') active @else disabled  @endif">
                    <input type="radio" name="status" id="signed" @if(optional($additionsReport)->status == 'signed') checked @endif disabled> unterschrieben
                </label>
                <label class="btn btn-outline-secondary @if(optional($additionsReport)->status == 'finished') active @else disabled  @endif">
                    <input type="radio" name="status" id="finished" @if(optional($additionsReport)->status == 'finished') checked @endif disabled> erledigt
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="services_provided_on">Datum</label>
            <input type="date" class="form-control @error('services_provided_on') is-invalid @enderror" id="services_provided_on" name="services_provided_on" placeholder="" value="{{ old('services_provided_on', optional(optional($additionsReport)->services_provided_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('services_provided_on')
                    {{ $message }}
                @else
                    Gib bitte das Datum der Leistungserbringung an.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="hours">Regiestunden</label>
            <div class="input-group">
                <input type="number" min="{{ $minAccountingAmount }}" step="{{ $minAccountingAmount }}" class="form-control @error('hours') is-invalid @enderror" id="hours" name="hours" placeholder="5" value="{{ old('hours', optional($additionsReport)->hours) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">
                    @error('hours')
                        {{ $message }}
                    @else
                        Gib bitte die Anzahl an Stunden an.
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="project_id">Projekt (Bauvorhaben)</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}"></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
            </svg>
            Anwesende Personen
        </p>
        <p class="text-muted">
            Anwensendes Personal sowie weitere Personen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="involved_ids">Personalstand</label>
            <people-selector inputname="involved_ids[]" :people="{{ $employees }}" :current_people="{{ $currentInvolvedEmployees ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('involved_ids') d-block @enderror">
                @error('involved_ids')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="present_ids">Anwesende Personen</label>
            <people-selector inputname="present_ids[]" :people="{{ $people }}" :current_people="{{ $currentPresentPeople ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('present_ids') d-block @enderror">
                @error('present_ids')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="other_visitors">Sonstige Besucher</label>
            <input type="text" class="form-control @error('other_visitors') is-invalid @enderror" id="other_visitors" name="other_visitors" placeholder="Max Mustermann" value="{{ old('other_visitors', optional($additionsReport)->other_visitors) }}" />
            <div class="invalid-feedback @error('other_visitors') d-block @enderror">
                @error('other_visitors')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
            </svg>
            Beeinflussende Faktoren
        </p>
        <p class="text-muted">
            Faktoren sowie Umstände, welche den Leistungsfortschritt beeinflussten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="inspection_comment">Güte- und Funktionsprüfung</label>
            <textarea class="form-control @error('inspection_comment') is-invalid @enderror" id="inspection_comment" name="inspection_comment" placeholder="Angaben zur Prüfung">{{ old('inspection_comment', optional($additionsReport)->inspection_comment) }}</textarea>
            <div class="invalid-feedback @error('inspection_comment') d-block @enderror">
                @error('inspection_comment')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="missing_documents">Fehlende Ausführungsunterlagen</label>
            <textarea class="form-control @error('missing_documents') is-invalid @enderror" id="missing_documents" name="missing_documents" placeholder="Angaben zu den fehlenden Unterlagen">{{ old('missing_documents', optional($additionsReport)->missing_documents) }}</textarea>
            <div class="invalid-feedback @error('missing_documents') d-block @enderror">
                @error('missing_documents')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="special_occurrences">Besondere Vorkommnisse</label>
            <textarea class="form-control @error('special_occurrences') is-invalid @enderror" id="special_occurrences" name="special_occurrences" placeholder="Angaben zu den Vorkommnissen">{{ old('special_occurrences', optional($additionsReport)->special_occurrences) }}</textarea>
            <div class="invalid-feedback @error('special_occurrences') d-block @enderror">
                @error('special_occurrences')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="imminent_danger">Gefahr in Verzug</label>
            <textarea class="form-control @error('imminent_danger') is-invalid @enderror" id="imminent_danger" name="imminent_danger" placeholder="Angaben zur Gefahr">{{ old('imminent_danger', optional($additionsReport)->imminent_danger) }}</textarea>
            <div class="invalid-feedback @error('imminent_danger') d-block @enderror">
                @error('imminent_danger')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="concerns">Bedenken</label>
            <textarea class="form-control @error('concerns') is-invalid @enderror" id="concerns" name="concerns" placeholder="Angaben zu den Bedenken">{{ old('concerns', optional($additionsReport)->concerns) }}</textarea>
            <div class="invalid-feedback @error('concerns') d-block @enderror">
                @error('concerns')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cloud"></use>
            </svg>
            Wetter
        </p>
        <p class="text-muted">
            Angaben zum Wetter sowie Temperaturen vor Ort.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div>
                <label for="priority">Wetter</label>
            </div>
            <div class="btn-group btn-group-toggle @error('weather') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('weather', optional($additionsReport)->weather) == 'sunny') active @endif">
                    <input type="radio" name="weather" id="sunny" value="sunny" autocomplete="off" @if(old('weather', optional($additionsReport)->weather) == 'sunny') checked @endif> sonnig
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($additionsReport)->weather) == 'cloudy') active @endif">
                    <input type="radio" name="weather" id="cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($additionsReport)->weather) == 'cloudy') checked @endif> bewölkt
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($additionsReport)->weather) == 'rainy') active @endif">
                    <input type="radio" name="weather" id="rainy" value="rainy" autocomplete="off" @if(old('weather', optional($additionsReport)->weather) == 'rainy') checked @endif> regnerisch
                </label>
                <label class="btn btn-outline-secondary @if(old('weather', optional($additionsReport)->weather) == 'snowy') active @endif">
                    <input type="radio" name="weather" id="snowy" value="snowy" autocomplete="off" @if(old('weather', optional($additionsReport)->weather) == 'snowy') checked @endif> Schnee
                </label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="minimum_temperature">Minimale Temperatur</label>
            <div class="input-group">
                <input type="number" step="1" class="form-control @error('minimum_temperature') is-invalid @enderror" id="minimum_temperature" name="minimum_temperature" placeholder="18" value="{{ old('minimum_temperature', optional($additionsReport)->minimum_temperature) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">°C</span>
                </div>
                <div class="invalid-feedback">
                    @error('minimum_temperature')
                    {{ $message }}
                    @else
                        Gib bitte die minimale Temperatur an.
                        @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="maximum_temperature">Maximale Temperatur</label>
            <div class="input-group">
                <input type="number" step="1" class="form-control @error('maximum_temperature') is-invalid @enderror" id="maximum_temperature" name="maximum_temperature" placeholder="22" value="{{ old('maximum_temperature', optional($additionsReport)->maximum_temperature) }}" required />
                <div class="input-group-append">
                    <span class="input-group-text">°C</span>
                </div>
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Leistungsfortschritt
        </p>
        <p class="text-muted">
            Leistungsfortschritt der Arbeiten.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Leistungsfortschritt
            </label>
            <vue-easymde :configs="{maxHeight: '300px', tabSize: 4, indentWithTabs: false, spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Leistungsfortschritt"  value="{{ old('comment', optional($additionsReport)->comment) }}" v-cloak></vue-easymde>
            <a class="text-muted d-inline-flex align-items-center mt-1" href="{{ route('help.show', 'markdown') }}">
                <svg class="icon icon-16 mr-1">
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
            </svg>
            Anhänge
        </p>
        <p class="text-muted">
            Dem Servicebericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.
        </p>
        <p class="text-muted">
            Der Dateiname von neu hinzugefügten Anhängen kann geändert werden, indem der Text markiert und ein neuer Name eingegeben wird.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
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
            <svg class="icon icon-16 mr-2">
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
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                Die Email Adresse kann im nächsten Schritt angegeben werden.
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('send_signature_request') is-invalid @enderror" name="send_signature_request" id="send_signature_request" value="true">
                <label class="custom-control-label" for="send_signature_request">Anfrage zur Unterschrift nach dem Speichern senden.</label>
            </div>
            <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                @error('send_signature_request')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
