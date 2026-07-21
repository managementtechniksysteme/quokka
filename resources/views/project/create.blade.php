@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Projekt anlegen</div>
                    <h1 class="q-title">Neues Projekt</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" action="{{ route('projects.store') }}" method="post" novalidate>
            @include('project.fields', ['project' => $project, 'currencyUnit' => $currencyUnit, 'currentCompany' => $currentCompany, 'companies' => $companies, 'removeFinishedProjectFinanceGroup' => $removeFinishedProjectFinanceGroup])

            <div class="q-form-actions">
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Projekt speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
