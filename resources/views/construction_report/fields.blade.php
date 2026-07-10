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
    <div class="q-banner">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
        <span>Du hast noch keine Unterschrift im System hinterlegt. Es kann nicht automatisch eine Unterschrift in PDF Ausdrucke von Berichten eingefügt werden. Füge bitte eine Unterschrift in den <a href="{{ route('user-settings.edit', ['tab' => 'general']) }}">allgemeinen Einstellungen</a> hinzu.</span>
    </div>
@endunless

<div class="q-form-section">
    <div class="q-form-section__head">
        Stammdaten
        <div class="q-form-section__desc">Die Stammdaten des Bautagesberichtes. Bei der Bearbeitung eines bereits unterschriebenen Bautagesberichtes wird die vorhandene Unterschrift entfernt.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="employee">Ersteller</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ optional($constructionReport)->employee->person->name ?? Auth::user()->person->name }}" disabled />
        </div>

        <div>
            <label>Status</label>
            @if(optional($constructionReport)->status === 'signed')
                <div class="q-banner">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <span>Der Bautagesbericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.</span>
                </div>
            @endif
            <div class="btn-group">
                <input type="radio" class="btn-check" name="status" id="status-new" autocomplete="off" @if(optional($constructionReport)->status == 'new' || !$constructionReport) checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--sky" for="status-new">neu</label>
                <input type="radio" class="btn-check" name="status" id="status-signed" autocomplete="off" @if(optional($constructionReport)->status == 'signed') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--amber" for="status-signed">unterschrieben</label>
                <input type="radio" class="btn-check" name="status" id="status-finished" autocomplete="off" @if(optional($constructionReport)->status == 'finished') checked @endif disabled>
                <label class="btn btn-outline-secondary q-seg--green" for="status-finished">erledigt</label>
            </div>
        </div>

        <div>
            <label for="services_provided_on">Datum</label>
            <input type="date" class="form-control @error('services_provided_on') is-invalid @enderror" id="services_provided_on" name="services_provided_on" value="{{ old('services_provided_on', optional(optional($constructionReport)->services_provided_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('services_provided_on')
                    {{ $message }}
                @else
                    Gib bitte das Datum der Leistungserbringung an.
                @enderror
            </div>
        </div>

        <div>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anwesende Personen
        <div class="q-form-section__desc">Anwesendes Personal sowie weitere Personen.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label>Personalstand</label>
            <people-selector inputname="involved_ids[]" :people="{{ $employees }}" :current_people="{{ $currentInvolvedEmployees ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('involved_ids') d-block @enderror">
                @error('involved_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Anwesende Personen</label>
            <people-selector inputname="present_ids[]" :people="{{ $people }}" :current_people="{{ $currentPresentPeople ?? 'null' }}" v-cloak></people-selector>
            <div class="invalid-feedback @error('present_ids') d-block @enderror">
                @error('present_ids')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label for="other_visitors">Sonstige Besucher</label>
            <input type="text" class="form-control @error('other_visitors') is-invalid @enderror" id="other_visitors" name="other_visitors" placeholder="Max Mustermann" value="{{ old('other_visitors', optional($constructionReport)->other_visitors) }}" />
            <div class="invalid-feedback">
                @error('other_visitors')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Beeinflussende Faktoren
        <div class="q-form-section__desc">Faktoren sowie Umstände, welche den Leistungsfortschritt beeinflussten.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="inspection_comment">Güte- und Funktionsprüfung</label>
            <textarea class="form-control @error('inspection_comment') is-invalid @enderror" id="inspection_comment" name="inspection_comment" placeholder="Angaben zur Prüfung">{{ old('inspection_comment', optional($constructionReport)->inspection_comment) }}</textarea>
            <div class="invalid-feedback">
                @error('inspection_comment')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <label for="missing_documents">Fehlende Ausführungsunterlagen</label>
            <textarea class="form-control @error('missing_documents') is-invalid @enderror" id="missing_documents" name="missing_documents" placeholder="Angaben zu den fehlenden Unterlagen">{{ old('missing_documents', optional($constructionReport)->missing_documents) }}</textarea>
            <div class="invalid-feedback">
                @error('missing_documents')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <label for="special_occurrences">Besondere Vorkommnisse</label>
            <textarea class="form-control @error('special_occurrences') is-invalid @enderror" id="special_occurrences" name="special_occurrences" placeholder="Angaben zu den Vorkommnissen">{{ old('special_occurrences', optional($constructionReport)->special_occurrences) }}</textarea>
            <div class="invalid-feedback">
                @error('special_occurrences')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <label for="imminent_danger">Gefahr in Verzug</label>
            <textarea class="form-control @error('imminent_danger') is-invalid @enderror" id="imminent_danger" name="imminent_danger" placeholder="Angaben zur Gefahr">{{ old('imminent_danger', optional($constructionReport)->imminent_danger) }}</textarea>
            <div class="invalid-feedback">
                @error('imminent_danger')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <label for="concerns">Bedenken</label>
            <textarea class="form-control @error('concerns') is-invalid @enderror" id="concerns" name="concerns" placeholder="Angaben zu den Bedenken">{{ old('concerns', optional($constructionReport)->concerns) }}</textarea>
            <div class="invalid-feedback">
                @error('concerns')
                    {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Wetter
        <div class="q-form-section__desc">Angaben zum Wetter sowie Temperaturen vor Ort.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label>Wetter</label>
            <div class="btn-group @error('weather') is-invalid @enderror">
                <input type="radio" class="btn-check" name="weather" id="weather-sunny" value="sunny" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'sunny') checked @endif>
                <label class="btn btn-outline-secondary q-seg--amber" for="weather-sunny">sonnig</label>
                <input type="radio" class="btn-check" name="weather" id="weather-cloudy" value="cloudy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'cloudy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-cloudy">bewölkt</label>
                <input type="radio" class="btn-check" name="weather" id="weather-rainy" value="rainy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'rainy') checked @endif>
                <label class="btn btn-outline-secondary q-seg--sky" for="weather-rainy">regnerisch</label>
                <input type="radio" class="btn-check" name="weather" id="weather-snowy" value="snowy" autocomplete="off" @if(old('weather', optional($constructionReport)->weather) == 'snowy') checked @endif>
                <label class="btn btn-outline-secondary" for="weather-snowy">Schnee</label>
            </div>
            <div class="invalid-feedback @error('weather') d-block @enderror">
                @error('weather')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="q-form__row q-form__row--2">
            <div>
                <label for="minimum_temperature">Minimale Temperatur</label>
                <div class="input-group has-validation">
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
            <div>
                <label for="maximum_temperature">Maximale Temperatur</label>
                <div class="input-group has-validation">
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
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Leistungsfortschritt
        <div class="q-form-section__desc">Leistungsfortschritt der Arbeiten.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Leistungsfortschritt" value="{{ old('comment', optional($constructionReport)->comment) }}" v-cloak></markdown-editor>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anhänge
        <div class="q-form-section__desc">Dem Bautagesbericht zugeordnete Anhänge. Erlaubt sind Dateien im Bildformat oder PDF Dokumente.</div>
    </div>
    <div class="q-form-section__body">
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anfrage zur Unterschrift senden
        <div class="q-form-section__desc">Bei Aktivierung kann nach dem Speichern direkt eine Anfrage zur Unterschrift per Email versendet werden.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div class="q-banner q-banner--info">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
            <span>Die Email Adresse kann im nächsten Schritt angegeben werden.</span>
        </div>
        <div>
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
