@csrf

<div class="row">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
            </svg>
            Teilrechnung Details
        </p>
        <p class="text-muted">
            Die Details der Teilrechnung.
        </p>
    </div>

    <div class="col-md-8">

        <div class="form-group">
            <label for="name">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Erste Teilrechnung" value="{{ old('title', optional($interimInvoice)->title) }}" required />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @else
                    Gib bitte den Titel der Teilrechnung ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="billed_on">Rechnungsdatum</label>
            <input type="date" class="form-control @error('billed_on') is-invalid @enderror" id="billed_on" name="billed_on" placeholder="" value="{{ old('billed_on', optional(optional($interimInvoice)->billed_on)->format('Y-m-d')) }}" required />
            <div class="invalid-feedback">
                @error('billed_on')
                {{ $message }}
	        @else
	          Gib bitte das Datum der Teilrechnung ein.
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="amount">Summe</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
                </div>
                <input type="number" min="0" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" placeholder="" value="{{ old('amount', optional($interimInvoice)->amount) }}" />
                <div class="invalid-feedback">
                    @error('amount')
                    {{ $message }}
		    @else
		      Gib bitte die Summe der Teilrechnung ein.
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
            Sonstige Bemerkungen zur Teilrechnung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label for="comment">
                Bemerkungen
            </label>
            <markdown-editor name="comment" placeholder="Bemerkungen zur Teilrechnung"  value="{{ old('comment', optional($interimInvoice)->comment) }}" v-cloak></markdown-editor>
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
