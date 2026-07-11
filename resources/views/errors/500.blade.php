@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-octagon"></use></svg>
@endsection
@section('message', __('Server Error'))
@section('description', 'Da ist etwas schiefgelaufen. Versuche es in Kürze noch einmal.')

@section('support')
    @if($exceptionUuid)
        <p class="q-error-eyebrow" style="margin-bottom: .35rem;">Fehler-ID für den Support</p>
        <p class="q-error-mono" style="margin: 0 0 .85rem;">{{ $exceptionUuid }}</p>

        <button id="uuid-button" class="q-error-btn" data-clipboard-text="{{ $exceptionUuid }}" type="button">
            <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
            <span id="uuid-button-text">Kopieren</span>
        </button>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script type="text/javascript">
        var clipboard = new ClipboardJS('#uuid-button');

        clipboard.on('success', function(e) {
            document.getElementById('uuid-button-text').innerHTML = "Kopiert!";
        });
    </script>
@endsection
