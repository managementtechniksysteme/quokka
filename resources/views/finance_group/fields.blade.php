@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Finanzgruppe Details
        <div class="q-form-section__desc">Die Details der Finanzgruppe.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="title">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Mustergruppe" value="{{ old('title', optional($financeGroup)->title) }}" />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <label>Bemerkungen</label>
            <markdown-editor name="comment" placeholder="Bemerkungen zur Finanzgruppe" value="{{ old('comment', optional($financeGroup)->comment) }}" v-cloak></markdown-editor>
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
</div>
