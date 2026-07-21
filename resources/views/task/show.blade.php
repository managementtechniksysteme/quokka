@extends('layouts.app')

{{-- Mobile app bar: back chevron + task name + kebab, same pattern as
     company/project's own show.blade.php (2026-07-21). Erledigen/Bearbeiten
     (separate buttons on desktop, there's room) fold into the sheet as its
     first two items instead, same reasoning as Bearbeiten did elsewhere. --}}
@section('mobile-detail-bar')
    <a href="{{ route('tasks.index') }}" class="q-appbar__btn" aria-label="Zurück zu Aufgaben">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $task->name }}</span>
    <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#taskShowActionsSheet" aria-controls="taskShowActionsSheet" aria-label="Aktionen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
    </button>
@endsection

@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="taskShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @unless($task->status === 'finished')
                @can('update', $task)
                    <a class="q-row" href="{{ route('tasks.finish', ['task' => $task, 'redirect' => 'show']) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span>
                        <span class="q-row__title">Erledigen</span>
                    </a>
                @endcan
            @endunless
            @can('update', $task)
                <a class="q-row" href="{{ route('tasks.edit', $task) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('create', \App\Models\Task::class)
                <a class="q-row" href="{{ route('tasks.create', ['template' => $task]) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#files"></use></svg></span>
                    <span class="q-row__title">Kopieren</span>
                </a>
            @endcan
            @can('email', $task)
                <a class="q-row" href="{{ route('tasks.email', ['task' => $task, 'redirect' => 'show']) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $task)
                <a class="q-row" href="{{ route('tasks.download', $task) }}" target="_blank">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $task)
                <form action="{{ route('tasks.destroy', $task) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('task.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
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
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
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

        <div class="q-statbar mb-4 mt-2 pt-1 mt-md-0 pt-md-0">
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
                    <div class="q-card__head">
                        <div class="d-flex align-items-start align-items-md-center flex-nowrap gap-2">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-baseline gap-md-2" style="row-gap:.1rem">
                                <span class="d-none d-md-inline">Aktivitäten und Diskussion</span>
                                <span class="d-inline d-md-none">Aktivitäten</span>
                                @unless($activities->isEmpty())
                                    <span class="q-subtitle mt-0">{{ trans_choice('messages.entries', $activities->total()) }}</span>
                                @endunless
                            </div>

                            @unless($activities->isEmpty())
                                @can('create', [\App\Models\TaskComment::class, $task])
                                    <a class="btn q-btn d-inline-flex align-items-center gap-2 ms-auto" href="{{ route('comments.create', ['task' => $task->id]) }}">
                                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                                        Kommentar
                                    </a>
                                @endcan
                            @endunless
                        </div>
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
                                <div class="q-empty-state">
                                    <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
                                    <p>Noch keine Aktivitäten zu dieser Aufgabe.</p>
                                    @can('create', [\App\Models\TaskComment::class, $task])
                                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('comments.create', ['task' => $task->id]) }}">
                                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
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
