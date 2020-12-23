<div class="lead">
    <a href="{{ route('people.index') }}">Personen</a> <span class="text-muted">/</span> <a href="{{ route('people.show', $person) }}">{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</a>
</div>
