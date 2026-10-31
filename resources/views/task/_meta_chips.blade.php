{{-- Status / visibility / priority chips of the task detail header. Rendered twice
     (desktop head + mobile copy). Editors get in-place quick-edit chips.
     Params: $task, $quickEdit (array from TaskController::quickEditData, or null). --}}
@if($quickEdit)
    <quick-edit-chip url="{{ route('tasks.quick-update', $task) }}" field="status" value="{{ $task->status }}" :options="{{ json_encode($quickEdit['status']) }}" chip_class="q-status" aria_label="Status ändern" :reload="true"></quick-edit-chip>

    @if($task->private || count($quickEdit['visibility']) > 1)
        <quick-edit-chip url="{{ route('tasks.quick-update', $task) }}" field="private" :value="{{ $task->private ? 'true' : 'false' }}" :options="{{ json_encode($quickEdit['visibility']) }}" aria_label="Sichtbarkeit ändern"></quick-edit-chip>
    @endif

    <quick-edit-chip url="{{ route('tasks.quick-update', $task) }}" field="priority" value="{{ $task->priority }}" :options="{{ json_encode($quickEdit['priority']) }}" prefix="Priorität " aria_label="Priorität ändern"></quick-edit-chip>
@else
    <span class="q-status q-status--{{ Str::slug($task->status) }}">{{ $task->status_label }}</span>

    @if($task->private)
        <span class="q-chip">
            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
            privat
        </span>
    @endif

    <span class="q-chip">
        <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#flag"></use></svg>
        Priorität {{ __($task->priority) }}
    </span>
@endif
