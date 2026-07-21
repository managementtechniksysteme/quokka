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

    {{-- iOS home-screen launch splash (2026-07-21) — Safari doesn't read the
         manifest's background_color/icons for this the way Android does, it
         needs an exact-pixel-match per physical device size or it just shows
         a bare white screen while the page loads (what the "few seconds of
         white" actually was). Android needs none of this — Chrome generates
         its own splash straight from manifest.json's background_color/icons/
         name, already present there. One PNG per unique modern iPhone screen
         size (de-duplicated — several models share identical specs) per
         theme, logo centered on the app's own --q-bg light/dark tokens (not
         new colors — the same ones _quokka-ui.scss themes everything else
         with), each light entry explicitly scoped to
         prefers-color-scheme:light (needed so it doesn't keep matching
         everywhere once the dark set exists — an unscoped link matches
         regardless of the system setting). Generated via Playwright (exact
         device `screen` + devicePixelRatio per model, not the smaller
         in-app viewport) — see public/icons/splash/. Portrait only;
         landscape PWA launch isn't a realistic case for this app. Covers
         iPhone X-class and newer (roughly the last ~6 years of hardware)
         plus the current SE — older devices fall back to the plain white
         screen, unchanged from today. --}}
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-375-667-2x-light.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-375-812-3x-light.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-414-896-2x-light.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-414-896-3x-light.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-390-844-3x-light.png" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-428-926-3x-light.png" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-393-852-3x-light.png" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-430-932-3x-light.png" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-402-874-3x-light.png" media="(device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-440-956-3x-light.png" media="(device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-420-912-3x-light.png" media="(device-width: 420px) and (device-height: 912px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: light)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-375-667-2x-dark.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-375-812-3x-dark.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-414-896-2x-dark.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-414-896-3x-dark.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-390-844-3x-dark.png" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-428-926-3x-dark.png" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-393-852-3x-dark.png" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-430-932-3x-dark.png" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-402-874-3x-dark.png" media="(device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-440-956-3x-dark.png" media="(device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">
    <link rel="apple-touch-startup-image" href="/icons/splash/apple-splash-420-912-3x-dark.png" media="(device-width: 420px) and (device-height: 912px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait) and (prefers-color-scheme: dark)">

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

<div id="app" @auth class="is-authed" @endauth>
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
