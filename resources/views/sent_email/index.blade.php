@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Gesendete Emails</h1>
                    @unless($activities->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $activities->total()) }}</div>
                    @endunless
                </div>
            </div>
        </div>

        @if(\App\Models\ApplicationSettings::get()->prune_sent_emails)
            <div class="q-banner mb-3">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                <span>Einträge, die älter als einen Monat sind, werden automatisch aus dem System entfernt.</span>
            </div>
        @endif

        @unless ($activities->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('sent-emails.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Gesendete Emails suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" href="{{ Request::url() }}">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endunless

        @if($activities->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                @if(Request::get('search'))
                    <p>Keine gesendeten E-Mails für diese Suche gefunden.</p>
                @else
                    <p>Noch keine E-Mails gesendet.</p>
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($activities as $activity)
                    @include('sent_email.overview_card', ['activity' => $activity])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
@endsection
