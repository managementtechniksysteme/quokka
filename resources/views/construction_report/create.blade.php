@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('construction-reports.index') }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">Bautagesbericht anlegen</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Bautagesbericht anlegen</div>
                    <h1 class="q-title">Neuer Bautagesbericht</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('construction-reports.store') }}" method="post" novalidate>
            @include('construction_report.fields', ['constructionReport' => $constructionReport, 'currentProject' => $currentProject, 'projects' => $projects, 'currentInvolvedEmployees' => $currentInvolvedEmployees, 'employees' => $employees, 'currentPresentPeople' => $currentPresentPeople, 'people' => $people, 'currentAttachments' => $currentAttachments, 'minAccountingAmount' => $minAccountingAmount])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('construction-reports.index') }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Bautagesbericht speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
