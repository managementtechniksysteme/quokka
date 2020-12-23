@php
    use \App\Models\Project;
@endphp

@if (old('project_id'))
    @php $currentProject = Project::find(old('project_id')); @endphp
@endif

@if (old('services'))
    @php $currentServices = json_encode(old('services')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Serviceberichtes.
        </p>
        <p class="text-muted">
            Bei der Bearbeitung eines bereits unterschriebenen Serviceberichtes wird die vorhandene Unterschrift entferent.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="employee">Techniker</label>
            <input type="text" class="form-control" name="employee" id="employee" placeholder="{{ Auth::user()->person->name }}" disabled />
        </div>

        <div class="form-group">
            <div>
                <label for="status">Status</label>
            </div>
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-outline-secondary @if(optional($serviceReport)->status == 'new' || !$serviceReport) active @else disabled @endif">
                    <input type="radio" name="status" id="new" @if(optional($serviceReport)->status == 'new' || !$serviceReport) checked @endif disabled> neu
                </label>
                <label class="btn btn-outline-secondary @if(optional($serviceReport)->status == 'signed') active @else disabled  @endif">
                    <input type="radio" name="status" id="signed" @if(optional($serviceReport)->status == 'signed') checked @endif disabled> unterschrieben
                </label>
                <label class="btn btn-outline-secondary @if(optional($serviceReport)->status == 'finished') active @else disabled  @endif">
                    <input type="radio" name="status" id="finished" @if(optional($serviceReport)->status == 'finished') checked @endif disabled> erledigt
                </label>
            </div>
            @if(optional($serviceReport)->status == 'signed')
                <div class="alert alert-warning mt-1" role="alert">
                    <div class="d-inline-flex align-items-center">
                        <svg class="feather feather-24 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                        </svg>
                        Der Servicebericht wurde bereits unterschrieben. Beim Speichern wird die aktuelle Unterschrift entfernt! Eine erneute Anfrage zum Unterschreiben kann gesendet werden.
                    </div>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="project_id">Projekt</label>
            <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}"></project-dropdown>
            <div class="invalid-feedback @error('project_id') d-block @enderror">
                @error('project_id')
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
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
            </svg>
            Vollbrachte Serviceleistungen
        </p>
        <p class="text-muted">
            Serviceleistungen werden automatisch nach Datum gruppiert und Werte entsprechend summiert.
        </p>
    </div>

    <div class="col-md-8">
        <services-selector :current_services="{{ $currentServices ?? 'null' }}"></services-selector>
        <div class="invalid-feedback @error('services') d-block @enderror @error('services.*') d-block @enderror">
            @error('services')
                {{ $message }}
            @enderror
            @error('services.*')
                {{ $message }}
            @enderror
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
            Sonstige Bemerkungen zum Servicebericht.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zur Aufgabe"  value="{{ old('comment', optional($serviceReport)->comment) }}"></vue-easymde>
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
