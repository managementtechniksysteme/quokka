@extends('layouts.app')

@if (old('email_to'))
    @php $currentTo = json_encode(old('email_to')); @endphp
@endif

@if (old('email_cc'))
    @php $currentCC = json_encode(old('email_cc')); @endphp
@endif

@if (old('email_bcc'))
    @php $currentBCC = json_encode(old('email_bcc')); @endphp
@endif

@section('mobile-detail-bar')
    <a href="{{ url()->previous() }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $task->name }}</span>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('task.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">{{ $task->name }}</div>
                    <h1 class="q-title">Aufgabe senden</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation mt-2 mt-md-4" action="{{ route('tasks.email', ['task' => $task, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">Empfänger</div>
                <div class="q-form-section__body">
                    <email-selector :people="{{ $people }}" :current_to="{{ $currentTo ?? '[]'}}" :current_cc="{{ $currentCC ?? '[]' }}" :current_bcc="{{ $currentBCC ?? '[]' }}"></email-selector>
                    <div class="invalid-feedback @error('email_to') d-block @enderror @error('email_to.*') d-block @enderror @error('email_cc') d-block @enderror @error('email_cc.*') d-block @enderror @error('email_bcc') d-block @enderror @error('email_bcc.*') d-block @enderror">
                        @error('email_to'){{ $message }}@enderror
                        @error('email_to.*'){{ $message }}@enderror
                        @error('email_cc'){{ $message }}@enderror
                        @error('email_cc.*'){{ $message }}@enderror
                        @error('email_bcc'){{ $message }}@enderror
                        @error('email_bcc.*'){{ $message }}@enderror
                    </div>
                </div>
            </div>

            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ url()->previous() }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                    Abbrechen
                </a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                    <span class="d-none d-md-inline">Aufgabe senden</span>
                    <span class="d-inline d-md-none">Senden</span>
                </button>
            </div>

        </form>

    </div>
@endsection
