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
    <!-- Android  -->
    <meta name="theme-color" content="white">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- iOS -->
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">

    <!-- Windows  -->
    <meta name="msapplication-navbutton-color" content="white">
    <meta name="msapplication-TileColor" content="white">
    <meta name="msapplication-TileImage" content="/icons/icon_144.png">

    <!-- Pinned Sites  -->
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="msapplication-tooltip" content="{{ config('app.name') }}">
    <meta name="msapplication-starturl" content="/">

    <!-- Tap highlighting  -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- UC Mobile Browser  -->
    <meta name="full-screen" content="yes">
    <meta name="browsermode" content="application">

    <!-- Disable night mode for this page  -->
    <meta name="nightmode" content="disable">

    <!-- Layout mode -->
    <meta name="layoutmode" content="standard">

    <!-- Styles and Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Main Link Tags  -->
    <link href="/icons/icon_48.png" rel="icon" type="image/png" sizes="48x48">
    <link href="/icons/icon_72.png" rel="icon" type="image/png" sizes="72x72">
    <link href="/icons/icon_96.png" rel="icon" type="image/png" sizes="96x96">
    <link href="/icons/icon_144.png" rel="icon" type="image/png" sizes="144x144">
    <link href="/icons/icon_192.png" rel="icon" type="image/png" sizes="192x192">
    <link href="/icons/icon_512.png" rel="icon" type="image/png" sizes="512x512">

    <!-- iOS  -->
    <link href="/icons/icon_72.png" rel="apple-touch-icon">
    <link href="/icons/icon_96.png" rel="apple-touch-icon" sizes="96x96">
    <link href="/icons/icon_144.png" rel="apple-touch-icon" sizes="144x144">
    <link href="/icons/icon_192.png" rel="apple-touch-icon" sizes="192x192">

    <!-- Startup Image  -->
    <link href="/icons/icon_512.png" rel="apple-touch-startup-image">

    <!-- Others -->
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <!-- UC Browser  -->
    <link href="/icons/icon_48.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="/icons/icon_72.png" rel="apple-touch-icon" sizes="72x72">

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
