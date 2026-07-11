@extends('user_settings.edit')

@section('tab')
    <form class="q-form needs-validation" action="{{ route('user-settings.update-notifications') }}" method="post" novalidate>
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Allgemeines
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                <div>
                    <label>Über eigene Aktionen benachrichtigen?</label>
                    <div class="btn-group @error('notify_self') is-invalid @enderror">
                        <input type="radio" class="btn-check" name="notify_self" id="notify_self-1" value="1" autocomplete="off" @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == true) checked @endif>
                        <label class="btn btn-outline-secondary" for="notify_self-1">benachrichtigen</label>
                        <input type="radio" class="btn-check" name="notify_self" id="notify_self-0" value="0" autocomplete="off" @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == false) checked @endif>
                        <label class="btn btn-outline-secondary" for="notify_self-0">nicht benachrichtigen</label>
                    </div>
                    <div class="invalid-feedback @error('notify_self') d-block @enderror">
                        @error('notify_self') {{ $message }} @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                        Einstellungen speichern
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="q-form-section mt-4">
        <div class="q-form-section__head">
            Push Benachrichtigungen
            <div class="q-form-section__desc">
                Push Benachrichtigungen müssen aus technischen Gründen auf jedem Gerät separat aktiviert beziehungsweise deaktiviert werden.
            </div>
        </div>
        <div class="q-form-section__body d-flex flex-column gap-3">
            <div><webpush-manager v-cloak></webpush-manager></div>
            @if(Auth::user()->push_subscriptions_count)
                <div>
                    <p class="mb-2">Push Benachrichtigungen testen. Es wird eine Test Benachrichtigung an
                        {{ trans_choice('messages.devices', Auth::user()->push_subscriptions_count, ['number' => Auth::user()->push_subscriptions_count]) }}
                        gesendet.</p>
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('webpush.test') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                        Test Benachrichtigung senden
                    </a>
                </div>
            @endif
        </div>
    </div>

    <form class="q-form needs-validation mt-4" action="{{ route('user-settings.update-notification-targets') }}" method="post" novalidate>
        @csrf

        <div class="q-form-section">
            <div class="q-form-section__head">
                Benachrichtigungsziele
                <div class="q-form-section__desc">
                    In {{ config('app.name') }} kannst du immer alle Benachrichtigungen einsehen. Die hier gesetzten Einstellungen beziehen sich auf externe Ziele.
                </div>
            </div>
            <div class="q-form-section__body d-flex flex-column gap-3">
                @unless(Auth::user()->push_subscriptions_count)
                    <div class="q-banner">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                        Du hast noch keine Geräte für Push Benachrichtigungen registriert. Die Einstellungen zu Push Zielen haben erst Auswirkungen, wenn du Geräte registrierst.
                    </div>
                @endif

                <div>
                    <div class="d-flex gap-3 px-2 pb-2 small text-uppercase text-muted">
                        <div class="flex-grow-1"></div>
                        <div style="width: 3rem">Email</div>
                        <div style="width: 3rem">Push</div>
                    </div>

                    @foreach($notificationCategories as $category)
                        <div class="d-flex align-items-center px-2 pb-1 small text-muted @unless($loop->first) pt-3 @endunless">
                            <svg class="icon-bs icon-baseline me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $category['icon'] }}"></use></svg>
                            {{ $category['label'] }}
                        </div>
                        @foreach($category['rows'] as $row)
                            @php $id = $notifications[$row['class']]; @endphp
                            <div class="hover-highlight d-flex align-items-center gap-3 py-1 px-2">
                                <div class="flex-grow-1" style="font-size: .85rem">{{ $row['label'] }}</div>
                                <div style="width: 3rem"><div class="form-check form-switch mb-0">
                                    <input type="checkbox" class="form-check-input"
                                           name="email[{{ $id }}]" id="email[{{ $id }}]" value="{{ $id }}"
                                           @if(old("email[$id]") || (!old('_token') && in_array($id, $emailNotifications))) checked @endif>
                                    <label class="form-check-label" for="email[{{ $id }}]"></label>
                                </div></div>
                                <div style="width: 3rem"><div class="form-check form-switch mb-0">
                                    <input type="checkbox" class="form-check-input"
                                           name="webpush[{{ $id }}]" id="webpush[{{ $id }}]" value="{{ $id }}"
                                           @if(old("webpush[$id]") || (!old('_token') && in_array($id, $webPushNotifications))) checked @endif>
                                    <label class="form-check-label" for="webpush[{{ $id }}]"></label>
                                </div></div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#floppy"></use></svg>
                        Einstellungen speichern
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection
