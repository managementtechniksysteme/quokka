<div class="lead text-muted d-flex align-items-center">
    <svg class="feather feather-16 mr-2">
        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
    </svg>
    <a href="{{ route('tasks.index') }}">Aufgaben</a>
    <span class="px-2">/</span>
    <a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a>
</div>
