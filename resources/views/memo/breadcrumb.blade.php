<div class="lead text-muted d-flex align-items-center">
    <svg class="icon icon-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
    </svg>
    <a href="{{ route('memos.index') }}">Aktenvermerke</a>
    <span class="px-2">/</span>
    <a href="{{ route('memos.show', $memo) }}">{{ $memo->title }}</a>
</div>
