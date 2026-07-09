<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Theme: set data-bs-theme before paint so there's no light flash. -->
    <meta name="color-scheme" content="light dark">
    <script>
        // Pref stored as: system | light | dark (System follows the OS setting,
        // which also lets extensions like DarkReader behave correctly).
        (function () {
            var KEY = 'quokka-theme';
            var mq = window.matchMedia('(prefers-color-scheme: dark)');
            function resolve(pref) { return pref === 'dark' || (pref !== 'light' && mq.matches) ? 'dark' : 'light'; }
            window.applyQuokkaTheme = function (pref) {
                pref = pref || localStorage.getItem(KEY) || 'system';
                var root = document.documentElement;
                root.setAttribute('data-bs-theme', resolve(pref));
                root.setAttribute('data-theme-pref', pref);
            };
            window.setQuokkaTheme = function (pref) { localStorage.setItem(KEY, pref); window.applyQuokkaTheme(pref); };
            window.applyQuokkaTheme();
            mq.addEventListener('change', function () {
                if ((localStorage.getItem(KEY) || 'system') === 'system') window.applyQuokkaTheme('system');
            });
        })();
    </script>

    <meta name="description" content="Die {{ config('app.name') }} Projektmanagement Applikation.">

    <!-- PWA -->
    <meta name="theme-color" content="#6366f1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-navbutton-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">
    <meta name="msapplication-TileImage" content="/icons/icon_144.png">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Styles and Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <link href="/icons/icon_192.png" rel="icon" type="image/png" sizes="192x192">
    <link href="/icons/icon_512.png" rel="icon" type="image/png" sizes="512x512">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @auth
        <script>
            window.VAPID_PUBLIC_KEY = {!! json_encode(config('webpush.vapid.public_key')) !!}
        </script>
        <script src="{{ asset('js/init.js') }}" defer></script>
    @endauth

    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">
</head>

<body>

<div id="app">
    @include('partials.notifications')
    @include('partials.navbar')

    <main class="q-page">
        @yield('content')
    </main>
</div>

@auth
    @livewire('livewire-ui-spotlight')
@endauth
</body>

</html>
