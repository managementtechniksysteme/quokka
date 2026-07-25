@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Hilfe</h1>
                    @if(count($names) > 0)
                        <div class="q-subtitle">{{ trans_choice('messages.entries', count($names)) }}</div>
                    @endif
                </div>
            </div>

            {{-- Mobile: the app bar already carries "Hilfe" + its own icon —
                 collapses to just the count, no repeated icon/title
                 (2026-07-21, user: "double headers"). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @if(count($names) > 0)
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', count($names)) }}</div>
                @endif
            </div>
        </div>

        @if(count($names) > 0)
            <div class="q-card q-list">
                @foreach($names as $name)
                    <div class="q-row">
                        <a class="stretched-link outline-none" href="{{ route('help.show', $name) }}"></a>
                        <span class="q-avatar">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg>
                        </span>
                        <div class="q-row__main">
                            <div class="q-row__title text-truncate">{{ Str::title(trans($name)) }}</div>
                        </div>

                        <svg class="icon-bs icon-16 q-row__chevron d-md-none"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
                    </div>
                @endforeach
            </div>
        @else
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg>
                <p>Keine Hilfethemen vorhanden.</p>
            </div>
        @endif
    </div>
@endsection
