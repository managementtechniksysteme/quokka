<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
    </svg>
    <a href="{{ route('notes.index') }}">Notizbuch</a>
    <span class="px-2">/</span>
    <a href="{{ route('notes.show', $note) }}">{{ $note->created_at->format('d.m.Y, H:i') }}</a>
</div>
