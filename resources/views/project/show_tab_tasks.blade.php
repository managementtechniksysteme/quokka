@extends('project.show')

@section('tab')
    @if ($project->tasks->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
            <p>Diesem Projekt sind noch keine Aufgaben zugeordnet.</p>
            @can('create', \App\Models\Task::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Aufgabe anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Aufgaben</h2>
            <div class="ms-auto d-flex align-items-center gap-2">
                @can('create', \App\Models\Task::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.create', ['project' => $project->id]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        <span class="d-none d-md-inline">Aufgabe anlegen</span>
                        <span class="d-inline d-md-none">Aufgabe</span>
                    </a>
                @endcan
                @can('downloadList', \App\Models\Task::class)
                    <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('tasks.download-list', ['project_id' => $project->id]) }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                    <a class="btn q-btn q-btn-icon d-md-none" href="{{ route('tasks.download-list', ['project_id' => $project->id]) }}" target="_blank" aria-label="PDF Liste">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                    </a>
                @endcan
            </div>
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Aufgaben suchen',
            'sorts' => ['due_on-asc' => 'Fällig am', 'due_on-desc' => 'Fällig am', 'name-asc' => 'Name', 'name-desc' => 'Name', 'status-asc' => 'Status', 'status-desc' => 'Status', 'priority-asc' => 'Priorität', 'priority-desc' => 'Priorität'],
            'quickFilters' => [
                'Meine Aufgaben' => 'v:' . $u->username . $fin,
                'Meine bald fälligen Aufgaben' => 'v:' . $u->username . ' ist:bald_fällig',
                'Meine überfälligen Aufgaben' => 'v:' . $u->username . ' ist:überfällig',
                'Beteiligte Aufgaben' => 'b:' . $u->username . $fin,
            ],
        ])

        @if ($tasks->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Aufgaben passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($tasks as $task)
                    @include('task.overview_card_content', ['task' => $task, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $tasks->links() }}</div>
        @endif
    @endif
@endsection
