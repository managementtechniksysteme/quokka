@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                </svg>
                Gesendete Emails
                @unless($activities->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.entries', $activities->total()) }}</small>
                @endunless
            </h3>
        </div>
    </div>

    <div class="container my-4">
        @if(\App\Models\ApplicationSettings::get()->prune_sent_emails)
            <div class="alert alert-warning mt-3" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    <p class="m-0">
                        Einträge, die älter als einen Monat sind, werden automatisch aus dem System entfernt.
                    </p>
                </div>
            </div>
        @endif

        @unless ($activities->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col">

                    <form action="{{ route('sent-emails.index') }}" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Gesendete Emails suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($activities as $activity)
                @component('sent_email.overview_card', [ 'activity' => $activity ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine gesendeten Emails passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine gesendeten Emails im System vorhanden.</p>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $activities->links() }}
        </div>

    </div>
@endsection
