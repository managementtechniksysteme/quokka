@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('task.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Aufgabe</div>
                    <h1 class="q-title">{{ $task->name }}</h1>
                    <div class="q-meta">
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
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($task->status === 'finished')
                    @can('update', $task)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => 'show']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $task)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.edit', $task) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="taskShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="taskShowDropdown">
                        @can('create', \App\Models\Task::class)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.create', ['template' => $task]) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg>
                                Kopieren
                            </a>
                        @endcan
                        @can('email', $task)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.email', ['task' => $task, 'redirect' => 'show']) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $task)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('tasks.download', $task) }}" target="_blank">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $task)
                            <form action="{{ route('tasks.destroy', $task) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Fällig</span>
                <span class="q-statbar__value">
                    @if($task->due_on)
                        {{ $task->due_on }}
                        @if($task->isOverdue())
                            <span class="q-statbar__sep">·</span> <span class="q-statbar__urgent">überfällig</span>
                        @elseif($task->isDueSoon())
                            <span class="q-statbar__sep">·</span> <span class="q-statbar__soon">bald fällig</span>
                        @endif
                    @else
                        <span class="q-statbar__value--empty">kein Datum</span>
                    @endif
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Zeitraum</span>
                <span class="q-statbar__value">{{ $task->starts_on ?: 'offen' }} → {{ $task->ends_on ?: 'offen' }}</span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Verrechnung</span>
                <span class="q-statbar__value">{{ __($task->billed_string) }}</span>
            </div>
        </div>

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
                @include('task.show_overview')

                <div class="q-card">
                    <div class="q-card__head d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-baseline gap-2">
                            <span>Aktivitäten und Diskussion</span>
                            @unless($activities->isEmpty())
                                <span class="q-subtitle">{{ trans_choice('messages.entries', $activities->total()) }}</span>
                            @endunless
                        </div>

                        @unless($activities->isEmpty())
                            @can('create', [\App\Models\TaskComment::class, $task])
                                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('comments.create', ['task' => $task->id]) }}">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                    Kommentar
                                </a>
                            @endcan
                        @endunless
                    </div>

                    <div class="q-card__body">
                        <div class="q-feed">
                            @forelse ($activities as $activity)
                                @switch(get_class($activity))
                                    @case(\Spatie\Activitylog\Models\Activity::class)
                                        @include('task.activity_card', [ 'activity' => $activity ])
                                        @break
                                    @case(\App\Models\TaskComment::class)
                                        @include('comment.overview_card_content', [ 'comment' => $activity ])
                                        @break
                                @endswitch
                            @empty
                                <div class="text-center py-4">
                                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                                    <p class="lead text-muted">Zu der Aufgabe {{ $task->name }} gibt es noch keine Aktivitäten.</p>
                                    @can('create', [\App\Models\TaskComment::class, $task])
                                        <p class="lead">Lege einen neuen Kommentar an.</p>
                                        <a class="btn btn-lg btn-primary d-inline-flex align-items-center gap-2" href="{{ route('comments.create', ['task' => $task->id]) }}">
                                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                            Kommentar anlegen
                                        </a>
                                    @endcan
                                </div>
                            @endforelse
                        </div>

                        @unless($activities->isEmpty())
                            <div class="mt-3">
                                {{ $activities->links() }}
                            </div>
                        @endunless
                    </div>
                </div>
            </div>

            <aside class="q-aside">
                <div class="q-aside__group">
                    <div class="q-aside__label">Verantwortlich</div>
                    <div class="q-aside__person">
                        @include('partials.employee_avatar', ['employee' => $task->responsibleEmployee, 'modifier' => 'q-avatar--sm'])
                        <span class="q-aside__name text-truncate">{{ $task->responsibleEmployee->person->name }}</span>
                    </div>
                </div>

                <div class="q-aside__group">
                    <div class="q-aside__label">Mitwirkende · {{ $task->involvedEmployees->count() }}</div>
                    @forelse($task->involvedEmployees as $employee)
                        <div class="q-aside__person q-aside__person--muted">
                            @include('partials.employee_avatar', ['employee' => $employee, 'modifier' => 'q-avatar--sm'])
                            <span class="q-aside__name text-truncate">{{ $employee->person->name }}</span>
                        </div>
                    @empty
                        <div class="q-aside__person q-aside__person--muted">
                            <span class="q-aside__name">keine Mitwirkenden</span>
                        </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
@endsection
