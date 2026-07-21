@extends('layouts.app')

{{-- Mobile app bar: "X" close + project name, same pattern as
     company/edit.blade.php (2026-07-21). --}}
@section('mobile-detail-bar')
    <a href="{{ route('projects.show', $project) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-lg"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $project->name }}</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('project.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Projekt bearbeiten</div>
                    <h1 class="q-title">{{ $project->name }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('projects.update', $project) }}" method="post" novalidate>
            @method('PATCH')
            @include('project.fields', ['project' => $project, 'currencyUnit' => $currencyUnit, 'currentCompany' => $currentCompany, 'companies' => $companies, 'removeFinishedProjectFinanceGroup' => $removeFinishedProjectFinanceGroup])

            {{-- Abbrechen dropped on mobile: the app bar's "X" already covers
                 it (2026-07-21, same pattern as company's forms). --}}
            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('projects.show', $project) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Projekt speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
