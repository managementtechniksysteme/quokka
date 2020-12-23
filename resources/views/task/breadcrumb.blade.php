<div class="lead">
    <a href="{{ route('tasks.index') }}">Aufgaben</a> <span class="text-muted">/</span> <a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a>
</div>
