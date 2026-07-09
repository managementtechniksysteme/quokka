<nav class="q-breadcrumb">
    <a href="{{ route('notes.index') }}">Notizbuch</a>
    <span class="q-breadcrumb__sep">/</span>
    <span>{{ $note->created_at->format('d.m.Y, H:i') }}</span>
</nav>
