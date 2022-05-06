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
            <label for="wage_costs">Lohnkosten</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" min="0" step="0.01" class="form-control @error('wage_costs') is-invalid @enderror" id="wage_costs" name="wage_costs" placeholder="" value="{{ old('wage_costs', optional($project)->wage_costs) }}" />
            </div>
            <div class="invalid-feedback">
                @error('wage_costs')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="material_costs">Materialkosten</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" min="0" step="0.01" class="form-control @error('material_costs') is-invalid @enderror" id="material_costs" name="material_costs" placeholder="" value="{{ old('material_costs', optional($project)->material_costs) }}" />
            </div>
            <div class="invalid-feedback">
                @error('material_costs')
                    {{ $message }}
                @enderror
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
            <vue-easymde :configs="{maxHeight: '300px', tabSize: 4, indentWithTabs: false, spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zum Projekt"  value="{{ old('comment', optional($project)->comment) }}" v-cloak></vue-easymde>
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
