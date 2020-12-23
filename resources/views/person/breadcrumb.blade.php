<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
    </svg>
    <a href="{{ route('people.index') }}">Personen</a>
    <span class="px-2">/</span>
    <a href="{{ route('people.show', $person) }}">{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</a>
</div>
