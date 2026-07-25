@csrf

<div class="q-form-section">
    <div class="q-form-section__head">
        Details der Notiz
        <div class="q-form-section__desc">Die Details der Notiz.</div>
    </div>
    <div class="q-form-section__body d-flex flex-column gap-3">
        <div>
            <label for="title">Titel</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Musternotiz" value="{{ old('title', optional($note)->title) }}" />
            <div class="invalid-feedback">
                @error('title')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div>
            <markdown-editor name="comment" placeholder="Bemerkungen zur Notiz" value="{{ old('comment', optional($note)->comment) }}" :employees="{{ $employees }}" v-cloak></markdown-editor>
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

<div class="q-form-section">
    <div class="q-form-section__head">
        Anhänge
        <div class="q-form-section__desc">Der Notiz zugeordnete Anhänge (Bildformate oder PDF). Der Dateiname neu hinzugefügter Anhänge kann durch Markieren und Überschreiben geändert werden.</div>
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
