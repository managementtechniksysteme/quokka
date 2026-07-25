@csrf

<input type="hidden" id="task_id" name="task_id" value="{{ $task->id }}">

<div class="q-form-section">
    <div class="q-form-section__head">
        Kommentar
        <div class="q-form-section__desc">Kommentar zur Aufgabe.</div>
    </div>
    <div class="q-form-section__body">
        <markdown-editor name="comment" placeholder="Kommentar" value="{{ old('comment', optional($comment)->comment) }}" :employees="{{ $employees }}" v-cloak></markdown-editor>
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
        <div class="q-form-section__desc">Dem Kommentar zugeordnete Anhänge (Bildformate oder PDF). Der Dateiname neu hinzugefügter Anhänge kann durch Markieren und Überschreiben geändert werden.</div>
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
