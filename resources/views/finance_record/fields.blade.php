@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
            </svg>
            Finanzeintrag Details
        </p>
        <p class="text-muted">
            Die Details des Finanzeintrags.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-group">
            <label for="name">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Mustereintrag" value="{{ old('title', optional($financeRecord)->title) }}" required />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @else
                    Gib bitte den Titel des Finanzeintrags ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="billed_on">Datum</label>
            <input type="date" class="form-control @error('billed_on') is-invalid @enderror" id="billed_on" name="billed_on" placeholder="" value="{{ old('billed_on', optional(optional($financeRecord)->billed_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('billed_on')
                {{ $message }}
                @else
                  Gib bitte das Datum des Finanzeintrags ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="amount">Betrag</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" placeholder="" value="{{ old('amount', optional($financeRecord)->amount) }}" />
                <div class="invalid-feedback">
                    @error('amount')
                    {{ $message }}
                    @else
                      Gib bitte den Betrag des Finanzeintrags ein.
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
            Bemerkungen
        </p>
        <p class="text-muted">
            Sonstige Bemerkungen zum Finanzeintrag.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zur Teilrechnung"  value="{{ old('comment', optional($financeRecord)->comment) }}" v-cloak></markdown-editor>
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
