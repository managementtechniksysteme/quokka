@csrf

<input type="hidden" id="task_id" name="task_id" value="{{ $task->id }}">

<div class="form-group">
    <vue-easymde :configs="{spellChecker: false, status: false, showIcons: ['strikethrough', 'table', ], hideIcons: ['guide', ] }" name="comment" placeholder="Bemerkungen zur Firma"  value="{{ old('comment', optional($comment)->comment) }}" v-cloak></vue-easymde>
    <a class="text-muted d-inline-flex align-items-center mt-1 w-auto" href="{{ route('help.show', 'markdown') }}">
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
