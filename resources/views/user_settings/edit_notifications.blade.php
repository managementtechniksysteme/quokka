@extends('user_settings.edit')

@section('tab')
    <form class="needs-validation" action="{{ route('user-settings.update-notifications') }}" method="post" novalidate>

        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                    </svg>
                    Allgemeine Benachrichtigungseinstellungen
                </p>
            </div>
        </div>

        <div class="row">

            <div class="col">
                <div class="form-group">
                    <div>
                        <label for="notify_self">Über eigene Aktionen benachrichtigen?</label>
                    </div>
                    <div class="btn-group btn-group-toggle @error('notify_self') is-invalid @enderror" data-toggle="buttons">
                        <label class="btn btn-outline-secondary @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == true) active @endif">
                            <input type="radio" name="notify_self" id="1" value="1" autocomplete="off" @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == true) checked @endif> benachrichtigen
                        </label>
                        <label class="btn btn-outline-secondary @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == false) active @endif">
                            <input type="radio" name="notify_self" id="0" value="0" autocomplete="off" @if(old('notify_self', optional(Auth::user()->settings)->notify_self) == false) checked @endif> nicht benachrichtigen
                        </label>
                    </div>
                    <div class="invalid-feedback @error('notify_self') d-block @enderror">
                        @error('notify_self')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Einstellungen speichern
                </button>
            </div>
        </div>

    </form>

    <div class="row mt-4">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#smartphone"></use>
                </svg>
                Push Benachrichtigungen
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Push Benachrichtigungen müssen aus technischen Gründen auf jedem Gerät separat aktiviert beziehungsweise
                    deaktiviert werden.
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col">
            <webpush-manager v-cloak></webpush-manager>
        </div>
    </div>

    @if(Auth::user()->push_subscriptions_count)

        <div class="row mt-4">
            <div class="col">
                <p>
                    Push Benachrichtigungen testen. Es wird eine Test Benachrichtigung an
                    {{ trans_choice('messages.devices', Auth::user()->push_subscriptions_count, ['number' => Auth::user()->push_subscriptions_count]) }}
                    gesendet.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('webpush.test') }}">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                    </svg>
                    Test Benachrichtigung senden
                </a>
            </div>
        </div>

    @endif

    <form class="needs-validation mt-4" action="{{ route('user-settings.update-notification-targets') }}" method="post" novalidate>

        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-branch"></use>
                    </svg>
                    Benachrichtigungsziele
                </p>
            </div>
        </div>

        <div class="alert alert-info mt-1" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                In {{ config('app.name') }} kannst du immer alle Benachrichtigungen einsehen. Die hier gesetzten
                Einstellungen beziehen sich auf externe Ziele.
            </div>
        </div>

        @unless(Auth::user()->push_subscriptions_count)
            <div class="alert alert-warning mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="icon icon-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                    </svg>
                    <p class="m-0">
                        Du hast noch keine Geräte für Push Benachrichtigungen registriert. Die Einstellungen zu Push
                        Zielen haben erst Auswirkungen, wenn du Geräte registrierst.
                    </p>
                </div>
            </div>
        @endif

        <div class="row mt-4">
            <div class="col">
                <table class="table table-sm table-borderless">
                    <thead>
                        <th scope="col"></th>
                        <th scope="col">Email</th>
                        <th scope="col">Webpush</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                                </svg>
                                Aktenvermerke
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einem Aktenvermerk beteiligt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\MemoInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\MemoInvolvedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\MemoInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\MemoInvolvedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\MemoInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                In einem Aktenvermerk erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\MemoMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\MemoMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\MemoMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\MemoMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\MemoMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                </svg>
                                Applikation
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einer neuen Applikationsversion
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ApplicationVersionUpdateNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ApplicationVersionUpdateNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ApplicationVersionUpdateNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ApplicationVersionUpdateNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ApplicationVersionUpdateNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Aufgaben
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einer Aufgabe beteiligt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\TaskInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\TaskInvolvedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\TaskInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\TaskInvolvedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\TaskInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                In einer Aufgabe erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\TaskMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\TaskMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\TaskMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\TaskMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\TaskMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                                </svg>
                                Aufgaben Kommentare
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einer Aufgabe beteiligt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\CommentInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\CommentInvolvedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\CommentInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\CommentInvolvedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\CommentInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                In einem Kommentar erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\CommentMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\CommentMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\CommentMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\CommentMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\CommentMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon-bs icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                </svg>
                                Bautagesberichte
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einem Bautagesbericht beteiligt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ConstructionReportInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportInvolvedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ConstructionReportInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportInvolvedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ConstructionReportInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                In einem Bautagesbericht erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ConstructionReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ConstructionReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ConstructionReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                Bei Unterschrift eines Bautagesberichtes
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ConstructionReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportSignedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ConstructionReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ConstructionReportSignedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ConstructionReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon-bs icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                </svg>
                                Prüfberichte
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                In einem Prüfbericht erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\InspectionReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\InspectionReportMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\InspectionReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\InspectionReportMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\InspectionReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                Bei Unterschrift eines Prüfberichtes
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\InspectionReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\InspectionReportSignedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\InspectionReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\InspectionReportSignedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\InspectionReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon-bs icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                </svg>
                                Regieberichte
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei einem Regiebericht beteiligt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\AdditionsReportInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportInvolvedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\AdditionsReportInvolvedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportInvolvedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\AdditionsReportInvolvedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                In einem Regiebericht erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\AdditionsReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\AdditionsReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\AdditionsReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                Bei Unterschrift eines Regieberichtes
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\AdditionsReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportSignedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\AdditionsReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\AdditionsReportSignedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\AdditionsReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Serviceberichte
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                In einem Servicebericht erwähnt
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ServiceReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ServiceReportMentionNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ServiceReportMentionNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ServiceReportMentionNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ServiceReportMentionNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover-highlight">
                            <td>
                                Bei Unterschrift eines Serviceberichtes
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\ServiceReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ServiceReportSignedNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\ServiceReportSignedNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\ServiceReportSignedNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\ServiceReportSignedNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted" colspan="3">
                                <svg class="icon icon-baseline mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#sun"></use>
                                </svg>
                                Urlaub
                            </td>
                        </tr>

                        <tr class="hover-highlight">
                            <td>
                                Bei Anpassung des verfügbaren Urlaubes
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="email[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]"
                                           id="email[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}"
                                           @if(old("email[{$notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class], $emailNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="email[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           name="webpush[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]"
                                           id="webpush[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]"
                                           value="{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}"
                                           @if(old("webpush[{$notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class]}]") || (!old('_token') && in_array($notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class], $webPushNotifications))) checked @endif
                                    >
                                    <label class="custom-control-label"
                                           for="webpush[{{ $notifications[\App\Notifications\HolidayAllowanceAdjustmentNotification::class] }}]">
                                    </label>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Einstellungen speichern
                </button>
            </div>
        </div>

    </form>

@endsection
