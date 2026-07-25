@extends('layouts.app')

@section('mobile-detail-bar')
    <a href="{{ route('memos.show', $memo) }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $memo->title }}</span>
@endsection

@section('content')
    <div class="q-container">
        <div class="d-none d-md-block">
            @include('memo.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Aktenvermerk bearbeiten</div>
                    <h1 class="q-title">{{ $memo->title }}</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation" enctype="multipart/form-data" action="{{ route('memos.update', $memo) }}" method="post" novalidate>
            @method('PATCH')
            @include('memo.fields', ['memo' => $memo, 'currentProject' => $currentProject, 'projects' => $projects, 'currentEmployeeComposer' => $currentEmployeeComposer, 'employees' => $employees, 'currentPersonRecipient' => $currentPersonRecipient, 'currentPresentPeople' => $currentPresentPeople, 'currentNotifiedPeople' => $currentNotifiedPeople, 'people' => $people, 'currentAttachments' => $currentAttachments])

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ route('memos.show', $memo) }}"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>Abbrechen</a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                    <span class="d-none d-md-inline">Aktenvermerk speichern</span>
                    <span class="d-inline d-md-none">Speichern</span>
                </button>
            </div>
        </form>
    </div>
@endsection
