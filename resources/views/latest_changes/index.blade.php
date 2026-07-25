@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Letzte Änderungen</h1>
                    @unless($results->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.elements', $results->total()) }}</div>
                    @endunless
                </div>
            </div>

            {{-- Mobile: the app bar already carries "Letzte Änderungen" + its
                 own icon — collapses to just the count, no repeated icon/title
                 (2026-07-21, user: "double headers"). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($results->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.elements', $results->total()) }}</div>
                @endunless
            </div>
        </div>

        @if($results->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
                <p>Keine Ergebnisse für diese Suche gefunden.</p>
            </div>
        @else
            @php
                $groups = [
                    ['label' => 'Heute',       'items' => $changesToday],
                    ['label' => 'Gestern',     'items' => $changesYesterday],
                    ['label' => 'Diese Woche', 'items' => $changesThisWeek],
                    ['label' => 'Vorher',      'items' => $changesOlderThanThisWeek],
                ];
            @endphp

            @foreach($groups as $group)
                @unless($group['items']->isEmpty())
                    <div class="q-section-label">
                        <span>{{ $group['label'] }}</span>
                    </div>
                    <div class="q-card q-list mb-4">
                        @foreach($group['items'] as $change)
                            @include('latest_changes.result_card', ['result' => $change])
                        @endforeach
                    </div>
                @endunless
            @endforeach

            <div class="mt-3">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection
