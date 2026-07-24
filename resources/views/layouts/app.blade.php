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
    {{-- Two theme-color metas, not one shared #6366f1 (2026-07-24) — theme-color
         tints the OS/browser chrome (status bar, address bar, task-switcher
         card) to blend with the page, so it should match the page's own
         background per theme (--q-bg in _quokka-ui.scss), not the accent
         color, which would stand out as a bright/dark bar either way. --}}
    <meta name="theme-color" content="#eef0f4" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0c0f13" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="application-name" content="{{ config('app.name') }}">

    <!-- Styles and Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <link href="/icons/icon_192.png" rel="icon" type="image/png" sizes="192x192">
    <link href="/icons/icon_512.png" rel="icon" type="image/png" sizes="512x512">

    {{-- iOS home-screen launch splash (2026-07-21, regenerated via a real
         committed script 2026-07-24) — Safari doesn't read the manifest's
         background_color/icons for this the way Android does, it needs an
         exact-pixel-match per physical device size or it just shows a bare
         white screen while the page loads. Android needs none of this —
         Chrome generates its own splash straight from manifest.json.
         One PNG per unique modern iPhone screen size (de-duplicated) per
         theme, logo centered on the app's own --q-bg light/dark tokens.
         Regenerate both the PNGs and this include with:
           npm run generate:pwa-splash (scripts/generate-pwa-splash.mjs) --}}
    @include('partials.pwa-splash-links')

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
