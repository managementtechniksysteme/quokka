@php use \App\Models\Company; @endphp

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
            </svg>
            Stammdaten
        </p>
        <p class="text-muted">
            Die Stammdaten des Projektes.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Musterprojekt" value="{{ old('name', optional($project)->name) }}" required />
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @else
                    Gib bitte den Namen des Projektes ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="starts_on">Startdatum</label>
            <input type="date" class="form-control @error('starts_on') is-invalid @enderror" id="starts_on" name="starts_on" placeholder="" value="{{ old('starts_on', optional(optional($project)->starts_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('starts_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="ends_on">Enddatum</label>
            <input type="date" class="form-control @error('ends_on') is-invalid @enderror" id="ends_on" name="ends_on" placeholder="" value="{{ old('ends_on', optional(optional($project)->ends_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('ends_on')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="is_pre_execution">Befindet sich das Projekt in der Vorphase?</label>
            </div>
            <div class="btn-group btn-group-toggle @error('is_pre_execution') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('is_pre_execution', optional($project)->is_pre_execution) == true) active @endif">
                    <input type="radio" name="is_pre_execution" id="1" value="1" autocomplete="off" @if(old('is_pre_execution', optional($project)->is_pre_execution) == true) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('is_pre_execution', optional($project)->is_pre_execution) == false) active @endif">
                    <input type="radio" name="is_pre_execution" id="0" value="0" autocomplete="off" @if(old('is_pre_execution', optional($project)->is_pre_execution) == false) checked @endif> nein
                </label>
            </div>
            <div class="invalid-feedback @error('is_pre_execution') d-block @enderror">
                @error('is_pre_execution')
                {{ $message }}
                @enderror
            </div>
        </div>

        @if(!$project?->include_in_finances && $project?->financeGroup()->exists())
            <div class="alert alert-warning mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    <p class="m-0">
                        Dem Projekt sind manuell angelegte Finanzeinträge zugeordnet. Diese werden beim setzten der
                        nachfolgenden Option auf <strong>ja</strong> aus dem System entfernt.
                    </p>
                </div>
            </div>
        @endif

        <div class="form-group">
            <div>
                <label for="include_in_finances">Sollen dem Projekt zugehörigen Leistungen in den Finanzen berücksichtigt werden?</label>
            </div>
            <div class="btn-group btn-group-toggle @error('include_in_finances') is-invalid @enderror" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if(old('include_in_finances', optional($project)->include_in_finances) == true) active @endif">
                    <input type="radio" name="include_in_finances" id="1" value="1" autocomplete="off" @if(old('include_in_finances', optional($project)->include_in_finances) == true) checked @endif> ja
                </label>
                <label class="btn btn-outline-secondary @if(old('include_in_finances', optional($project)->include_in_finances) == false) active @endif">
                    <input type="radio" name="include_in_finances" id="0" value="0" autocomplete="off" @if(old('include_in_finances', optional($project)->include_in_finances) == false) checked @endif> nein
                </label>
            </div>
            <div class="invalid-feedback @error('include_in_finances') d-block @enderror">
                @error('include_in_finances')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="wage_costs">Lohnkosten</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" min="0" step="0.01" class="form-control @error('wage_costs') is-invalid @enderror" id="wage_costs" name="wage_costs" placeholder="" value="{{ old('wage_costs', optional($project)->wage_costs) }}" />
                <div class="invalid-feedback @error('wage_costs') d-block @enderror">
                    @error('wage_costs')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="material_costs">Materialkosten</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" min="0" step="0.01" class="form-control @error('material_costs') is-invalid @enderror" id="material_costs" name="material_costs" placeholder="" value="{{ old('material_costs', optional($project)->material_costs) }}" />
                <div class="invalid-feedback @error('material_costs') d-block @enderror">
                    @error('material_costs')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="company_id">Firma</label>
            <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}" v-cloak></company-dropdown>
            <div class="invalid-feedback @error('company_id') d-block @enderror">
                @error('company_id')
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
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zum Projekt.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zum Projekt"  value="{{ old('comment', optional($project)->comment) }}" v-cloak></markdown-editor>
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
