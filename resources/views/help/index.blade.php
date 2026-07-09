@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
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
