@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Finanzeintrag Details
        <div class="q-form-section__desc">Die Details des Finanzeintrags.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="title">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Mustereintrag" value="{{ old('title', optional($financeRecord)->title) }}" required />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @else
                    Gib bitte den Titel des Finanzeintrags ein.
                @enderror
            </div>
        </div>

        <div>
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

        <div class="d-flex flex-column gap-2">
            <div class="q-banner q-banner--info mb-0">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
                <span>Bei Einnahmen wird ein <strong>positiver</strong> Betrag eingegeben. Für Ausgaben wird ein <strong>negativer</strong> Wert verwendet.</span>
            </div>
            <div>
                <label for="amount">Betrag</label>
                <div class="input-group has-validation">
                    <span class="input-group-text">{{ $currencyUnit }}</span>
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
</div>

<div class="q-form-section">
    <div class="q-form-section__head">
        Bemerkungen
        <div class="q-form-section__desc">Sonstige Bemerkungen zum Finanzeintrag.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Bemerkungen zum Finanzeintrag" value="{{ old('comment', optional($financeRecord)->comment) }}" :employees="{{ $employees }}" v-cloak></markdown-editor>
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
