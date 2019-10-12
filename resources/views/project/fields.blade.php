@php use \App\Company; @endphp

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif

@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
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
            <label for="name_2">Startdatum</label>
            <input type="date" class="form-control @error('starts_on') is-invalid @enderror" id="starts_on" name="starts_on" placeholder="" value="{{ old('starts_on', optional(optional($project)->starts_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('starts_on')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name_2">Enddatum</label>
            <input type="date" class="form-control @error('ends_on') is-invalid @enderror" id="ends_on" name="ends_on" placeholder="" value="{{ old('ends_on', optional(optional($project)->ends_on)->format('Y-m-d')) }}" />
            <div class="invalid-feedback">
                @error('ends_on')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="company_id">Firma</label>
            <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}"></company-dropdown>
            <div class="invalid-feedback">
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
            <svg class="feather feather-16 mr-2">
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
            <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zum Projekt"  value="{{ old('comment', optional($project)->comment) }}"></vue-easymde>
            <div class="invalid-feedback">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
            <a class="text-muted d-flex align-items-center mt-1" href="#">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe zu Markdown
            </a>
        </div>

    </div>
</div>
