<nav class="q-breadcrumb">
    <a href="{{ route('people.index') }}">Personen</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $person->title_prefix }} {{ $person->name }} {{ $person->title_suffix }}</span>
</nav>
